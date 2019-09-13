<?php

namespace tiFy\Form\Factory;

use tiFy\Contracts\Form\FactoryView as FactoryViewContract;
use tiFy\View\ViewController;
use Closure;

class View extends ViewController implements FactoryViewContract
{
    /**
     * Liste des méthodes héritées.
     * @var array
     */
    protected $mixins = [];

    /**
     * {@inheritdoc}
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, $this->mixins)) :
            return call_user_func_array(
                [$this->engine->get('form'), $name],
                $arguments
            );
        endif;

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function after()
    {
        if ($content = $this->form()->get('after')) :
            if ($content instanceof Closure) :
                return call_user_func($content);
            elseif (is_string($content)) :
                return $content;
            endif;
        endif;

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function before()
    {
        if ($content = $this->form()->get('before')) :
            if ($content instanceof Closure) :
                return call_user_func($content);
            elseif (is_string($content)) :
                return $content;
            endif;
        endif;

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function form()
    {
        return $this->engine->get('form');
    }
}