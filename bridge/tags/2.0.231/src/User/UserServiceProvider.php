<?php declare(strict_types=1);

namespace tiFy\User;

use tiFy\Container\ServiceProvider;
use tiFy\User\Metadata\Metadata;
use tiFy\User\Metadata\Option as MetaOption;
use tiFy\User\Role\RoleFactory;
use tiFy\User\Role\RoleManager;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = [
        'user',
        'user.meta',
        'user.option',
        'user.role',
        'user.role.factory',
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('user', function () {
            return new User($this->getContainer());
        });

        $this->getContainer()->share('user.meta', function () {
            return new Metadata();
        });

        $this->getContainer()->share('user.option', function () {
            return new MetaOption();
        });

        $this->getContainer()->share('user.role', function () {
            return new RoleManager($this->getContainer());
        });

        $this->getContainer()->add('user.role.factory', function () {
            return new RoleFactory();
        });
    }
}