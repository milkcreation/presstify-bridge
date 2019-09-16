<?php

namespace tiFy\Kernel\Params;

use Illuminate\Support\Arr;

/**
 * Trait ParamsBagTrait
 * @package tiFy\Kernel\Params
 *
 * @mixin \tiFy\Contracts\Kernel\ParamsBag
 *
 * @deprecated Utiliser tiFy\Support\ParamsBag en remplacement.
 */
trait ParamsBagTrait
{
    /**
     * Liste des paramètres.
     * @var array
     */
    protected $attributes = [];

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function defaults()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = '')
    {
        return Arr::get($this->attributes, $key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return Arr::has($this->attributes, $key);
    }

    /**
     * {@inheritdoc}
     */
    public function keys()
    {
        return array_keys($this->attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function parse($attrs = [])
    {
        $this->attributes = array_merge(
            $this->attributes,
            $this->defaults(),
            $attrs
        );
    }

    /**
     * {@inheritdoc}
     */
    public function pull($key, $default = null)
    {
        return Arr::pull($this->attributes, $key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function push($key, $value)
    {
        if (!$this->has($key)) :
            $this->set($key, []);
        endif;

        $arr = $this->get($key);

        if (!is_array($arr)) :
            return false;
        else :
            array_push($arr, $value);
            $this->set($key, $arr);

            return true;
        endif;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $k => $v) :
            Arr::set($this->attributes, $k, $v);
        endforeach;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function unshift($value, $key)
    {
        if (!$this->has($key)) :
            $this->set($key, []);
        endif;

        $arr = $this->get($key);

        if (!is_array($arr)) :
            return false;
        else :
            array_unshift($arr, $value);
            $this->set($key, $arr);

            return true;
        endif;
    }

    /**
     * {@inheritdoc}
     */
    public function values()
    {
        return array_values($this->attributes);
    }
}