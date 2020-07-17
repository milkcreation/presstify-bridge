<?php declare(strict_types=1);

namespace tiFy\Wordpress\Session;

use tiFy\Contracts\Session\Session as BaseSession;

class Session
{
    /**
     * Instance du controleur de gestion des formulaires.
     * @var BaseSession
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param BaseSession $session
     *
     * @return void
     */
    public function __construct(BaseSession $session)
    {
        $this->manager = $session;

        events()->listen('session.get.stored', function (&$value) {
            $value = wp_unslash($value);
        });
    }
}