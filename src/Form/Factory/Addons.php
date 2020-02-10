<?php declare(strict_types=1);

namespace tiFy\Form\Factory;

use tiFy\Contracts\Form\{
    AddonFactory as AddonFactoryContract,
    FactoryAddons,
    FormFactory
};
use tiFy\Form\AddonFactory;
use tiFy\Support\Collection;

class Addons extends Collection implements FactoryAddons
{
    use ResolverTrait;

    /**
     * Liste des éléments associés au formulaire.
     * @var AddonFactoryContract[]|array
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

        foreach($addons as $name => $attrs) {
            if (is_numeric($name)) {
                $name = is_string($attrs) ? $attrs : null;
            }

            if ( ! is_null($name) && ($attrs !== false)) {
                $attrs = is_array($attrs) ? $attrs : [$attrs];

                $this->items[$name] = (app()->has("form.addon.{$name}"))
                    ? $this->resolve("addon.{$name}")
                    : (new AddonFactory())->setName($name);

                $this->items[$name]->setForm($this->form())->setParams($attrs)->boot();

                app()->share("form.factory.addon.{$name}.{$this->form()->name()}", $this->items[$name]);
            }
        }

        $this->events('addons.init', [&$this]);
    }
}