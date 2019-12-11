<?php declare(strict_types=1);

namespace tiFy\User;

use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\User\User as UserContract;
use tiFy\User\Metadata\Metadata;
use tiFy\User\Metadata\Option;
use tiFy\Contracts\User\RoleManager;

class User implements UserContract
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container
     */
    protected $container;

    /**
     * CONSTRUCTEUR.
     *
     * @param Container $container Conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @inheritdoc
     */
    public function meta(): Metadata
    {
        return $this->resolve('meta');
    }

    /**
     * @inheritdoc
     */
    public function option(): Option
    {
        return $this->resolve('option');
    }

    /**
     * @inheritdoc
     */
    public function role(): RoleManager
    {
        return $this->resolve('role');
    }

    /**
     * @inheritdoc
     */
    public function resolve($alias)
    {
        return $this->getContainer()->get("user.{$alias}");
    }
}
