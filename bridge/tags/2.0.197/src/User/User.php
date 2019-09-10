<?php declare(strict_types=1);

namespace tiFy\User;

use Psr\Container\ContainerInterface;
use tiFy\Contracts\User\User as UserContract;
use tiFy\User\Metadata\Metadata;
use tiFy\User\Metadata\Option;
use tiFy\Contracts\User\RoleManager;
use tiFy\Contracts\User\SessionManager;
use tiFy\Contracts\User\SigninManager;
use tiFy\Contracts\User\SignupManager;

class User implements UserContract
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var ContainerInterface
     */
    protected $container;

    /**
     * CONSTRUCTEUR.
     *
     * @param ContainerInterface $container Conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function getContainer(): ContainerInterface
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
    public function session(): SessionManager
    {
        return $this->resolve('session');
    }

    /**
     * @inheritdoc
     */
    public function signin(): SigninManager
    {
        return $this->resolve('signin');
    }

    /**
     * @inheritdoc
     */
    public function signup(): SignupManager
    {
        return $this->resolve('signup');
    }

    /**
     * @inheritdoc
     */
    public function resolve($alias)
    {
        return $this->container->get("user.{$alias}");
    }
}
