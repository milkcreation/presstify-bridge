<?php

namespace tiFy\Form\Factory;

use tiFy\Contracts\Form\AddonController;
use tiFy\Contracts\Form\FactoryAddons;
use tiFy\Contracts\Form\FormFactory;
use tiFy\Form\Factory\ResolverTrait as FormFactoryResolver;
use tiFy\Kernel\Collection\Collection;

class Addons extends Collection implements FactoryAddons
{
    use FormFactoryResolver;

    /**
     * Liste des éléments associés au formulaire.
     * @var AddonController[]
     */
    protected $items = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param array $addons Liste des addons associés au formulaire.
     * @param FormFactory $form Instance du contrôleur de formulaire.
     *
     * @return void
     */
    public function __construct($addons, FormFactory $form)
    {
        $this->form = $form;

        foreach($addons as $name => $attrs) :
            if (is_numeric($name)) :
                $name = is_string($attrs) ? $attrs : null;
            endif;

            if (!is_null($name) && ($attrs !== false)) :
                $attrs = is_array($attrs) ? $attrs : [$attrs];

                $this->items[$name] = (app()->has("form.addon.{$name}"))
                    ? $this->resolve("addon.{$name}", [$name, $attrs, $this->form()])
                    : $this->resolve("addon", [$name, $attrs, $this->form()]);

                app()->share("form.factory.addon.{$name}.{$this->form()->name()}", $this->items[$name]);
            endif;
        endforeach;

        $this->events('addons.init', [&$this]);
    }
}