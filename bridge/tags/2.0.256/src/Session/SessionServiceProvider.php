<?php declare(strict_types=1);

namespace tiFy\Session;

use tiFy\Container\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'session',
        'session.flashbag',
        'session.store'
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('session', function () {
            $session = new Session($this->getContainer());

            if (session_status() == PHP_SESSION_NONE) {
                $session->start();
            }

            return $session;
        });

        $this->getContainer()->add('session.flashbag', function () {
            return new FlashBag();
        });

        $this->getContainer()->add('session.store', function () {
            return new Store($this->getContainer()->get('session'));
        });
    }
}
