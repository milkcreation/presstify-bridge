<?php declare(strict_types=1);

namespace tiFy\Form\Factory;

use tiFy\Contracts\Form\{FactoryField, FactoryFields, FormFactory};
use tiFy\Support\Collection;

class Fields extends Collection implements FactoryFields
{
    use ResolverTrait;

    /**
     * Liste des éléments associés au formulaire.
     * @var FactoryField[]
     */
    protected $items = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param array $fields Liste des champs associés au formulaire.
     * @param FormFactory $form Instance du contrôleur de formulaire.
     *
     * @return void
     */
    public function __construct($fields, FormFactory $form)
    {
        $this->form = $form;

        // Déclaration des champs.
        foreach ($fields as $slug => $attrs) {
            if (!is_null($slug)) {
                /* @var FactoryField $item */
                $item = $this->items[$slug] = $this->resolve("factory.field", [$slug, $attrs, $this->form()]);

                app()->share("form.factory.field.{$this->form()->name()}.{$slug}", $item);

                if (!$item->getGroup()) {
                    $this->groups()->set($item->get('group', 0), []);
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function fromGroup(string $name): ?iterable
    {
        return $this->collect()->filter(function (FactoryField $field) use ($name) {
            return $field->getGroup()->getName() === $name;
        });
    }
}