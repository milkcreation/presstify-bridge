<?php declare(strict_types=1);

namespace tiFy\Mail;

use tiFy\Container\ServiceProvider;
use tiFy\Mail\Adapter\PhpMailer as AdapterPhpMailer;
use PHPMailer\PHPMailer\PHPMailer;
use tiFy\Support\Proxy\View;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @var string[]
     */
    protected $provides = [
        'mailer',
        'mail.queue',
        'mailer.library',
        'mailer.viewer',
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->add('mailer', function () {
            return new Mailer();
        });

        $this->getContainer()->add('mailer.library', function () {
            switch(config('mail.library')) {
                default :
                    $adapter = new AdapterPhpMailer(new PHPMailer(true));
                    break;
            }

            return $adapter;
        });

        $this->getContainer()->share('mail.queue', function () {
            return new MailQueue();
        });

        $this->getContainer()->add('mailer.viewer', function(array $attrs = []) {
            return View::getPlatesEngine(array_merge([
                'directory' => __DIR__ . '/Resources/views',
                'factory'   => MailerView::class
            ], $attrs));
        });
    }
}