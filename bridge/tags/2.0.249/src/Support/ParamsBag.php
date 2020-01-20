<?php declare(strict_types=1);

namespace tiFy\Support;

use Illuminate\Support\Arr;
use ArrayIterator;
use tiFy\Contracts\Support\ParamsBag as ParamsBagContract;

class ParamsBag implements ParamsBagContract
{
    /**
     * Liste des paramètres.
     * @var array
     */
    protected $attributes = [];

    /**
     * @inheritdoc
     */
    public static function createFromAttrs($attrs): ParamsBagContract
    {
        return (new static())->set($attrs)->parse();
    }

    /**
     * Récupération d'un élément d'itération.
     *
     * @param string|int $key Clé d'indexe.
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Définition d'un élément d'itération.
     *
     * @param string|int $key Clé d'indexe.
     * @param mixed $value Valeur.
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * Vérification d'existance d'un élément d'itération.
     *
     * @param string|int $key Clé d'indexe.
     *
     * @return boolean
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Suppression d'un élément d'itération.
     *
     * @param string|int $key Clé d'indexe.
     *
     * @return void
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
         $this->attributes = [];

         return $this;
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->attributes);
    }

    /**
     * @inheritDoc
     */
    public function defaults()
    {
        return [];
    }


    /**
     * @inheritDoc
     */
    public function forget($keys): void
    {
        Arr::forget($this->attributes, $keys);
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = '')
    {
        return Arr::get($this->attributes, $key, $default);
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->attributes);
    }

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        return Arr::has($this->attributes, $key);
    }

    /**
     * @inheritDoc
     */
    public function json($options = 0)
    {
        return json_encode($this->all(), $options);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->all();
    }

    /**
     * @inheritDoc
     */
    public function keys()
    {
        return array_keys($this->attributes);
    }

    /**
     * @inheritDoc
     */
    public function map(&$value, $key)
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($key)
    {
        unset($this->attributes[$key]);
    }

    /**
     * @inheritDoc
     */
    public function only($keys): array
    {
        return Arr::only($this->attributes, $keys);
    }

    /**
     * @inheritDoc
     */
    public function parse()
    {
        $this->attributes = array_merge($this->defaults(), $this->attributes);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function pull($key, $default = null)
    {
        return Arr::pull($this->attributes, $key, $default);
    }

    /**
     * @inheritDoc
     */
    public function push($key, $value)
    {
        if (!$this->has($key)) {
            $this->set($key, []);
        }

        $arr = $this->get($key);

        if (is_array($arr)) {
            array_push($arr, $value);
            $this->set($key, $arr);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        array_walk($keys, [$this, 'map']);

        foreach ($keys as $k => $v) {
            Arr::set($this->attributes, $k, $v);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function unshift($value, $key)
    {
        if (!$this->has($key)) {
            $this->set($key, []);
        }

        $arr = $this->get($key);

        if (is_array($arr)) {
            array_unshift($arr, $value);
            $this->set($key, $arr);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function values()
    {
        return array_values($this->attributes);
    }
}