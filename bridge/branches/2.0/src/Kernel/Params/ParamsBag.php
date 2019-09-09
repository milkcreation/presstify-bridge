<?php

namespace tiFy\Kernel\Params;

use ArrayIterator;
use tiFy\Contracts\Kernel\ParamsBag as ParamsBagContract;

/**
 * Class ParamsBag
 * @package tiFy\Kernel\Params
 *
 * @deprecated Utiliser tiFy\Support\ParamsBag en remplacement.
 */
class ParamsBag implements ParamsBagContract
{
    use ParamsBagTrait;

    /**
     * CONSTRUCTEUR.
     *
     * @param null|array $attrs Liste des paramètres personnalisés.
     *
     * @return void
     */
    public function __construct($attrs = null)
    {
        if (!is_null($attrs)) :
            $this->parse($attrs);
        endif;
    }

    /**
     * {@inheritdoc}
     */
    public function __call($method, $parameters)
    {
        $this->attributes[$method] = count($parameters) > 0 ? $parameters[0] : true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($key)
    {
        unset($this->attributes[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}