<?php declare(strict_types=1);

namespace tiFy\User\Role;

use tiFy\Contracts\User\{RoleFactory as RoleFactoryContract, RoleManager as RoleManagerContract};
use tiFy\Support\Manager;

class RoleManager extends Manager implements RoleManagerContract
{
    /**
     * Liste des éléments déclarés.
     * @var RoleFactoryContract[]
     */
    protected $items = [];

    /**
     * {@inheritDoc}
     *
     * @return RoleFactoryContract
     */
    public function get(...$args): ?RoleFactoryContract
    {
        return parent::get($args[0]);
    }

    /**
     * {@inheritDoc}
     *
     * @return RoleManagerContract
     */
    public function register($name, ...$args): RoleManagerContract
    {
        return $this->set([$name => $args[0] ?? []]);
    }

    /**
     * {@inheritDoc}
     *
     * @return RoleManagerContract
     */
    public function set($key, $value = null): RoleManagerContract
    {
        parent::set($key, $value);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function walk(&$item, $key = null): void
    {
        if (!$item instanceof RoleFactoryContract) {
            $name = $key;
            $attrs = $item;
            $item = $this->container ? $this->container->get('user.role.factory') : new RoleFactory();
        } else {
            $name = null;
            $attrs = [];
        }

        $item->prepare($this, $name, $attrs);
    }
}