<?php

namespace tiFy\Db;

use tiFy\Contracts\Db\DbFactory;
use tiFy\Contracts\Db\DbManager as DbManagerContract;

final class DbManager implements DbManagerContract
{
    /**
     * Liste des instances déclarées.
     * @var DbFactory[]
     */
    protected $items = [];

    /**
     * @inheritdoc
     */
    public function get($name)
    {
        return $this->items[$name] ?? null;
    }

    /**
     * @inheritdoc
     */
    public function register($name, $attrs = [])
    {
        if ($item = $this->get($name)) :
            return $item;
        endif;

        $controller = $attrs['controller'] ?? null;

        return $this->set(
            $name,
            ($controller ? new $controller($name, $attrs) : app()->get('db.factory', [$name, $attrs]))
        );
    }

    /**
     * @inheritdoc
     */
    public function set($name, DbFactory $factory)
    {
        return $this->items[$name] = $factory;
    }
}