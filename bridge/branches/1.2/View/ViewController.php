<?php

namespace tiFy\View;

use Illuminate\Support\Arr;
use League\Plates\Template\Template;
use tiFy\Kernel\Tools;

class ViewController extends Template
{
    /**
     * Instance du controleur de gestion des gabarits.
     * @var ViewEngine
     */
    protected $engine;

    /**
     * CONSTRUCTEUR.
     *
     * @param ViewEngine $engine
     * @param string $name
     *
     * @return void
     */
    public function __construct(ViewEngine $engine, $name)
    {
        parent::__construct($engine, $name);

        $this->boot();
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function fetch($name, array $data = [])
    {
        return $this->engine->render(
            ($this->engine->getFolders()->exists('_override')
                ? '_override::'
                : ''
            ) . $name,
            $data
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = '')
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return Arr::has($this->data, $key);
    }

    /**
     * {@inheritdoc}
     */
    public function htmlAttrs($attrs, $linearized = true)
    {
        return Tools::Html()->parseAttrs($attrs, $linearized);
    }

    /**
     * {@inheritdoc}
     */
    public function insert($name, array $data = [])
    {
        echo $this->fetch($name, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function reset($name)
    {
        $this->start($name); $this->stop();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function share($datas)
    {
        return $this->engine->addData($datas);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        return Arr::set($this->data, $key, $value);
    }
}