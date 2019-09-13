<?php

namespace tiFy\Form\Factory;

use tiFy\Contracts\Form\AddonController;
use tiFy\Contracts\Form\ButtonController;
use tiFy\Contracts\Form\FactoryAddons;
use tiFy\Contracts\Form\FactoryButtons;
use tiFy\Contracts\Form\FactoryEvents;
use tiFy\Contracts\Form\FactoryField;
use tiFy\Contracts\Form\FactoryFields;
use tiFy\Contracts\Form\FactoryGroup;
use tiFy\Contracts\Form\FactoryGroups;
use tiFy\Contracts\Form\FactoryNotices;
use tiFy\Contracts\Form\FactoryOptions;
use tiFy\Contracts\Form\FactoryRequest;
use tiFy\Contracts\Form\FactorySession;
use tiFy\Contracts\Form\FactoryValidation;
use tiFy\Contracts\Form\FactoryView;
use tiFy\Contracts\Form\FormFactory;
use tiFy\Contracts\View\ViewController;
use tiFy\Contracts\View\ViewEngine;
use tiFy\Contracts\Form\FactoryResolver;

/**
 * Trait ResolverTrait
 * @package tiFy\Form\Factory
 *
 * @mixin FactoryResolver
 */
trait ResolverTrait
{
    /**
     * Instance du controleur de champ associé.
     * @var FactoryField
     */
    protected $field;

    /**
     * Instance du controleur de formulaire associé.
     * @var FormFactory
     */
    protected $form;

    /**
     * {@inheritdoc}
     *
     * @return AddonController
     */
    public function addon($name)
    {
        return $this->addons()->get($name);
    }

    /**
     * {@inheritdoc}
     *
     * @return FactoryAddons|AddonController[]
     */
    public function addons()
    {
        return $this->resolve("factory.addons.{$this->form()->name()}");
    }

    /**
     * {@inheritdoc}
     *
     * @return FactoryButtons|ButtonController[]
     */
    public function buttons()
    {
        return $this->resolve("factory.buttons.{$this->form()->name()}");
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed|FactoryEvents
     */
    public function events($name = null)
    {
        /** @var FactoryEvents $factory */
        $factory = $this->resolve("factory.events.{$this->form()->name()}");

        if (is_null($name)) :
            return $factory;
        endif;

        return call_user_func_array([$factory, 'trigger'], func_get_args());
    }

    /**
     * {@inheritdoc}
     *
     * @return FactoryField
     */
    public function field($slug = null)
    {
        if (is_null($slug)) :
            return $this->field;
        endif;

        return $this->fields()->get($slug);
    }

    /**
     * {@inheritdoc}
     *
     * @return FactoryFields|FactoryField[]
     */
    public function fields()
    {
        return $this->resolve("factory.fields.{$this->form()->name()}");
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function fieldTagValue($tags, $raw = true)
    {
        if (is_string($tags)) :
            if (preg_match_all('#([^%%]*)%%(.*?)%%([^%%]*)?#', $tags, $matches)) :
                $tags = '';
                foreach ($matches[2] as $i => $slug) :
                    $tags .= $matches[1][$i] . (($field = $this->field($slug)) ? $field->getValue($raw) : $matches[2][$i]) . $matches[3][$i];
                endforeach;
            endif;
        elseif (is_array($tags)) :
            foreach ($tags as $k => &$i) :
                $i = $this->fieldTagValue($i, $raw);
            endforeach;
        endif;

        return $tags;
    }

    /**
     * {@inheritdoc}
     *
     * @return FormFactory
     */
    public function form()
    {
        return $this->form;
    }

    /**
     * {@inheritdoc}
     *
     * @return FactoryGroup|null
     */
    public function fromGroup(string $name)
    {
        return $this->groups()->get($name);
    }


    /**
     * {@inheritdoc}
     *
     * @return FactoryGroups|FactoryGroup[]
     */
    public function groups()
    {
        return $this->resolve("factory.groups.{$this->form()->name()}");
    }

    /**
     * {@inheritdoc}
     *
     * @return FactoryNotices
     */
    public function notices()
    {
        return $this->resolve("factory.notices.{$this->form()->name()}");
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function option($key = null, $default = null)
    {
        if (is_null($key)) :
            return $this->options()->all();
        endif;

        return $this->options()->get($key, $default);
    }

    /**
     * {@inheritdoc}
     *
     * @return FactoryOptions
     */
    public function options()
    {
        return $this->resolve("factory.options.{$this->form()->name()}");
    }

    /**
     * {@inheritdoc}
     *
     * @return FactoryRequest
     */
    public function request()
    {
        return $this->resolve("factory.request.{$this->form()->name()}");
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function resolve($alias, $args = [])
    {
        return app()->get("form.{$alias}", $args);
    }

    /**
     * {@inheritdoc}
     *
     * @return FactorySession
     */
    public function session()
    {
        return $this->resolve("factory.session.{$this->form()->name()}");
    }

    /**
     * {@inheritdoc}
     *
     * @return FactoryValidation
     */
    public function validation()
    {
        return $this->resolve("factory.validation.{$this->form()->name()}");
    }

    /**
     * {@inheritdoc}
     *
     * @return FactoryView|ViewController|ViewEngine
     */
    public function viewer($view = null, $data = [])
    {
        /** @var ViewEngine $viewer */
        $viewer = $this->resolve("factory.viewer.{$this->form()->name()}");

        if (is_null($view)) :
            return $viewer;
        endif;

        return $viewer->make("_override::{$view}", $data);
    }
}