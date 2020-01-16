<?php declare(strict_types=1);

namespace tiFy\Auth;

use tiFy\Auth\{Signin\Signin, Signup\Signup};
use tiFy\Container\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'auth',
        'auth.signin',
        'auth.signup'
    ];

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('after_setup_theme', function () {
            $this->getContainer()->get('auth');
        });
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->add('auth', function () {
            return new Auth($this->getContainer());
        });

        $this->getContainer()->add('auth.signin', function () {
            return new Signin($this->getContainer()->get('auth'));
        });

        $this->getContainer()->add('auth.signup', function () {
            return new Signup($this->getContainer()->get('auth'));
        });
    }
}