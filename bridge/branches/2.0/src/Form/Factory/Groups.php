<?php declare(strict_types=1);

namespace tiFy\Form\Factory;

use tiFy\Contracts\Form\FactoryField;
use tiFy\Contracts\Form\FactoryGroup;
use tiFy\Contracts\Form\FactoryGroups;
use tiFy\Contracts\Form\FormFactory;
use tiFy\Support\Collection;

class Groups extends Collection implements FactoryGroups
{
    use ResolverTrait;

    /**
     * Valeur incrémentale de l'indice de qualification d'un élément.
     * @var int
     */
    protected $_itemIndex = 0;

    /**
     * Liste des groupes déclarés rangés par parent.
     * @var FactoryGroup[][]
     */
    protected $grouped = [];

    /**
     * Liste des groupe déclarés.
     * @var FactoryGroup[]
     */
    protected $items = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param array $groups Liste des groupes de champs associés au formulaire.
     * @param FormFactory $form Instance du contrôleur de formulaire.
     *
     * @return void
     */
    public function __construct($groups, FormFactory $form)
    {
        $this->form = $form;

        $this->set($groups);
    }

    /**
     * @inheritdoc
     */
    public function getGrouped(string $parent = ''): ?iterable
    {
        return $this->grouped[$parent] ?? [];
    }

    /**
     * @inheritdoc
     */
    public function getIncreasedIndex(): int
    {
        return $this->_itemIndex++;
    }

    /**
     * @inheritdoc
     */
    public function prepare(): FactoryGroups
    {
        // Position des groupes.
        $max = $this->collect()->max(function (FactoryGroup $group) {
            return $group->getPosition();
        });

        $pad = 0;
        $this->collect()->each(function (FactoryGroup $group) use (&$pad, $max) {
            return $group->set('position', (int) $group->getPosition() ? : ++$pad+$max);
        });

        $this->grouped = $this->collect()->groupBy('parent');

        foreach ($this->items as $group) {
            if ($fields = $group->getFields()) {
                $max = $fields->max(function (FactoryField $field) { return $field->getPosition(); });
                $pad = 0;

                $fields->each(function (FactoryField $field) use (&$pad, $max) {
                    $number = 10000 * ($field->getGroup()->getPosition()+1);
                    $position = $field->getPosition() ?: ++$pad + $max;

                    return $field->setPosition(absint($number + $position));
                });
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function walk($item, $key = null): FactoryGroup
    {
        if (!$item instanceof FactoryGroup) {
            $item = new Group(array_merge(['name' => $key], (array)$item));
        }

        return $this->items[$item->getName()] = $item->prepare($this);
    }
}