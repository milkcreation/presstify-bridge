<?php declare(strict_types=1);

namespace tiFy\User\Signup;

use tiFy\Contracts\User\SignupFactory as SignupFactoryContract;
use tiFy\Contracts\User\SignupManager as SignupManagerContract;

class SignupManager implements SignupManagerContract
{
    /**
     * Liste des éléments déclarés.
     * @var SignupFactoryContract[]
     */
    protected $items = [];

    /**
     * @inheritdoc
     */
    public function get(string $name): ?SignupFactoryContract
    {
        return $this->items[$name] ?? null;
    }

    /**
     * @inheritdoc
     */
    public function register(string $name, array $attrs): SignupManagerContract
    {
        $controller = $attrs['controller'] ?? null;

        /** @var SignupFactoryContract $factory */
        $factory = $controller ? new $controller($name, $attrs) : new SignupFactory($name, $attrs);

        $this->set($factory);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function set(SignupFactoryContract $factory, ?string $name = null): SignupManagerContract
    {
        $this->items[$name ? : $factory->getName()] = $factory;

        return $this;
    }
}