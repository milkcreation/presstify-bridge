<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Dropdown;

use tiFy\Contracts\Partial\{Dropdown as DropdownContract, PartialFactory as PartialFactoryContract};
use tiFy\Contracts\Partial\DropdownItems as DropdownItemsContract;
use tiFy\Partial\PartialFactory;

class Dropdown extends PartialFactory implements DropdownContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'         => [],
            'after'         => '',
            'before'        => '',
            'viewer'        => [],
            'button'    => '',
            'items'     => [],
            'open'      => false,
            'trigger'   => false
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialFactoryContract
    {
        parent::parse();

        $this->set('attrs.data-control', 'dropdown');
        $this->set('attrs.data-id', $this->getId());

        $classes = [
            'button'    => 'PartialDropdown-button',
            'listItems' => 'PartialDropdown-items',
            'item'      => 'PartialDropdown-item'
        ];
        foreach($classes as $key => &$class) {
            $class = sprintf($this->get("classes.{$key}", '%s'), $class);
        }
        $this->set('classes', $classes);

        $items = $this->get('items', []);

        if (!$items instanceof DropdownItemsContract) {
            $items = new DropdownItems($items);
        }
        $this->set('items', $items->setPartial($this));

        $this->set('attrs.data-options', [
            'classes' => $this->get('classes', []),
            'open'    => $this->get('open'),
            'trigger' => $this->get('trigger'),
        ]);

        return $this;
    }
}