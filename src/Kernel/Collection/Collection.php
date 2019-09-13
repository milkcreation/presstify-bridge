<?php

namespace tiFy\Kernel\Collection;

use Illuminate\Support\Collection as IlluminateCollection;
use ArrayIterator;
use tiFy\Contracts\Kernel\Collection as CollectionContract;

/**
 * Class Collection
 * @package tiFy\Kernel\Collection
 *
 * @deprecated Utiliser \tiFy\Support\Collection
 */
class Collection implements CollectionContract
{
    /**
     * Liste des éléments.
     * @var array
     */
    protected $items = [];

    /**
     * Récupération de l'iteration courante.
     * @var ArrayIterator
     */
    protected $_iteration;

    /**
     * @inheritdoc
     */
    public function collect($items = null)
    {
        return is_null($items) ? new IlluminateCollection($this->items) : new IlluminateCollection($items);
    }

    /**
     * @inheritdoc
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return $this->getIteration()->current();
    }

    /**
     * @inheritdoc
     */
    public function exists()
    {
        return !empty($this->items);
    }

    /**
     * @inheritdoc
     */
    public function get($key, $default = null)
    {
        return $this->items[$key] ?? $default;
    }

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return isset($this->items[$key]);
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->getIteration()->key();
    }

    /**
     * @inheritdoc
     */
    public function wrap($item, $key = null)
    {
        return $this->items[$key] = $item;
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return $this->_iteration = new ArrayIterator($this->items);
    }

    /**
     * @inheritdoc
     */
    public function getIteration()
    {
        return ($this->_iteration instanceof ArrayIterator) ? $this->_iteration : $this->getIterator();
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) :
            $this->items[] = $value;
        else :
            $this->items[$key] = $value;
        endif;
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }

    /**
     * @inheritdoc
     */
    public function pluck($value, $key = null)
    {
        return $this->collect()->pluck($value, $key)->all();
    }

    /**
     * @inheritdoc
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @inheritdoc
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @inheritdoc
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * @inheritdoc
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }
}