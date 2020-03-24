<?php declare(strict_types=1);

namespace tiFy\Mail;

use tiFy\Contracts\Mail\{
    Mailer as MailerContract,
    MailerDriver as MailerDriverContract,
    MailerQueue as MailerQueueContract
};
use tiFy\Container\ServiceProvider;
use tiFy\Mail\Driver\PhpMailerDriver;
use PHPMailer\PHPMailer\PHPMailer;
use tiFy\Support\Proxy\View;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @var string[]
     */
    protected $provides = [
        'mail.view',
        'mailer',
        'mailer.driver',
        'mailer.queue',
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->add('mailer', function (): MailerContract {
            return (new Mailer())->setContainer($this->getContainer()->get('app'));
        });

        $this->getContainer()->add('mailer.driver', function (): MailerDriverContract {
            if (!($driver = config('mail.driver', null)) instanceof MailerDriverContract) {
                $driver = new PhpMailerDriver(new PHPMailer(env('APP_DEBUG')));
            }

            return $driver;
        });

        $this->getContainer()->share('mailer.queue', function (): MailerQueueContract {
            return (new MailerQueue())->setMailer($this->getContainer()->get('mailer'));
        });

        $this->getContainer()->add('mail.view', function (Mailer $mailer) {
            return View::getPlatesEngine(array_merge([
                'directory' => __DIR__ . '/Resources/views/',
                'factory'   => MailView::class,
                'mailer'    => $mailer,
            ], config('mail.viewer', []), $mailer->create()->params('viewer', [])));
        });
    }
}