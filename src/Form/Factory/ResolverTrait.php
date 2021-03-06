<?php declare(strict_types=1);

namespace tiFy\Form\Factory;

use tiFy\Contracts\Form\AddonFactory;
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
use tiFy\Contracts\Form\FormFactory;
use tiFy\Contracts\View\Engine as ViewEngine;
use tiFy\Contracts\Form\FactoryResolver;

/**
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
     * {@inheritDoc}
     *
     * @return AddonFactory|null
     */
    public function addon($name): ?AddonFactory
    {
        return $this->addons()->get($name);
    }

    /**
     * {@inheritDoc}
     *
     * @return FactoryAddons|AddonFactory[]
     */
    public function addons()
    {
        return $this->resolve("factory.addons.{$this->form()->name()}");
    }

    /**
     * {@inheritDoc}
     *
     * @return FactoryButtons|ButtonController[]
     */
    public function buttons()
    {
        return $this->resolve("factory.buttons.{$this->form()->name()}");
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed|FactoryEvents
     */
    public function events($name = null, array $args = [])
    {
        /** @var FactoryEvents $factory */
        $factory = $this->resolve("factory.events.{$this->form()->name()}");

        if (is_null($name)) {
            return $factory;
        }

        return call_user_func_array([$factory, 'trigger'], [$name, $args]);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @return FactoryFields|FactoryField[]
     */
    public function fields()
    {
        return $this->resolve("factory.fields.{$this->form()->name()}");
    }

    /**
     * {@inheritDoc}
     *
     * @return FormFactory
     */
    public function form()
    {
        return $this->form;
    }

    /**
     * {@inheritDoc}
     *
     * @return FactoryGroup|null
     */
    public function fromGroup(string $name)
    {
        return $this->groups()->get($name);
    }


    /**
     * {@inheritDoc}
     *
     * @return FactoryGroups|FactoryGroup[]
     */
    public function groups()
    {
        return $this->resolve("factory.groups.{$this->form()->name()}");
    }

    /**
     * {@inheritDoc}
     *
     * @return FactoryNotices
     */
    public function notices()
    {
        return $this->resolve("factory.notices.{$this->form()->name()}");
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @return FactoryOptions
     */
    public function options()
    {
        return $this->resolve("factory.options.{$this->form()->name()}");
    }

    /**
     * {@inheritDoc}
     *
     * @return FactoryRequest
     */
    public function request()
    {
        return $this->resolve("factory.request.{$this->form()->name()}");
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function resolve($alias, $args = [])
    {
        return app()->get("form.{$alias}", $args);
    }

    /**
     * {@inheritDoc}
     *
     * @return FactorySession
     */
    public function session()
    {
        return $this->resolve("factory.session.{$this->form()->name()}");
    }

    /**
     * {@inheritDoc}
     *
     * @return FactoryValidation
     */
    public function validation()
    {
        return $this->resolve("factory.validation.{$this->form()->name()}");
    }

    /**
     * {@inheritDoc}
     *
     * @return ViewEngine|string
     */
    public function viewer(?string $view = null, array $data = [])
    {
        /** @var ViewEngine $viewer */
        $viewer = $this->resolve("factory.viewer.{$this->form()->name()}");

        if (is_null($view)) {
            return $viewer;
        }

        return $viewer->render($view, $data);
    }
}