<?php

namespace tiFy\Wordpress\Mail;

class Mail
{
    /**
     * CONSTRUCTEUR
     *
     * @return void
     */
    public function __construct()
    {
        app()->add('mailer', function () {
            return new Mailer();
        });
    }
}