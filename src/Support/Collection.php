<?php declare(strict_types=1);

namespace tiFy\Support;

use ArrayIterator;
use Illuminate\Support\Collection as IlluminateCollection;
use tiFy\Contracts\Support\Collection as CollectionContract;
use Traversable;

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
     * @inheritDoc
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @inheritDoc
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * @inheritDoc
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * @inheritDoc
     */
    public static function createFromItems(array $items): CollectionContract
    {
        return (new static())->set($items);
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function chunk(int $size): iterable
    {
        return $this->collect()->chunk($size);
    }

    /**
     * @inheritDoc
     */
    public function clear(): CollectionContract
    {
        $this->items = [];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function collect($items = null)
    {
        return is_null($items) ? new IlluminateCollection($this->items) : new IlluminateCollection($items);
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->getIteration()->current();
    }

    /**
     * @inheritDoc
     */
    public function exists()
    {
        return !empty($this->items);
    }

    /**
     * @inheritDoc
     */
    public function get($key)
    {
        return $this->items[$key] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return $this->_iteration = new ArrayIterator($this->items);
    }

    /**
     * @inheritDoc
     */
    public function getIteration()
    {
        return ($this->_iteration instanceof ArrayIterator) ? $this->_iteration : $this->getIterator();
    }

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        return isset($this->items[$key]);
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->getIteration()->key();
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }

    /**
     * @inheritDoc
     */
    public function pluck($value, $key = null)
    {
        return $this->collect()->pluck($value, $key)->all();
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value = null): CollectionContract
    {
        if (is_array($key)) {
            $keys = $key;
        } elseif ($key instanceof Traversable) {
            $keys = iterator_to_array($key);
        } else {
            $keys = [$key => $value];
        }

        array_walk($keys, [$this, 'walk']);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function walk($value, $key = null)
    {
        return $this->items[$key] = $value;
    }
}