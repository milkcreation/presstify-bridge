<?php

namespace tiFy\Mail;

use tiFy\Container\ServiceProvider;
use tiFy\Mail\Adapter\PhpMailer as AdapterPhpMailer;
use PHPMailer\PHPMailer\PHPMailer;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @var string[]
     */
    protected $provides = [
        'mail.queue',
        'mailer',
        'mailer.library',
        'mailer.message.viewer',
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->getContainer()->add('mailer', function () {
            return new Mailer();
        });

        $this->getContainer()->add('mailer.library', function () {
            switch(config('mail.library')) :
                default :
                    $adapter = new AdapterPhpMailer(new PHPMailer(true));
                    break;
            endswitch;

            return $adapter;
        });

        $this->getContainer()->share('mail.queue', function () {
            return new MailQueue();
        });

        $this->getContainer()->add('mailer.message.viewer', function($attrs = []) {
            $default_dir = __DIR__ . '/Resources/views';
            $override_dir = $attrs['override_dir'] ?? '';

            return view()
                ->setDirectory($default_dir)
                ->setController(MailerMessageView::class)
                ->setOverrideDir(($override_dir && is_dir($override_dir)) ? $override_dir : $default_dir);
        });
    }
}