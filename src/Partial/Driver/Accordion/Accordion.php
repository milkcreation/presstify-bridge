<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Accordion;

use tiFy\Contracts\Partial\{Accordion as AccordionContract, PartialDriver as PartialDriverContract};
use tiFy\Partial\PartialDriver;

class Accordion extends PartialDriver implements AccordionContract
{
    /**
     * {@inheritDoc}
     *
     * @var array $attrs Attributs HTML du champ.
     * @var string $after Contenu placé après le champ.
     * @var string $before Contenu placé avant le champ.
     * @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * @var string $theme Theme d'affichage. light|dark.
     * @var array|AccordionItem[]|AccordionWalker $items Liste des éléments.
     * @var mixed $opened Définition de la liste des éléments ouverts à l'initialisation.
     * @var boolean $multiple Activation de l'ouverture multiple d'éléments.
     * @var boolean $triggered Activation de l'ouverture et la fermeture du volet par un déclencheur dédié.
     */
    public function defaults(): array
    {
        return [
            'attrs'     => [],
            'after'     => '',
            'before'    => '',
            'viewer'    => [],
            'items'     => [],
            'multiple'  => false,
            'parent'    => null,
            'opened'    => null,
            'theme'     => 'light',
            'triggered' => false,
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialDriverContract
    {
        parent::parse();

        if ($theme = $this->get('theme')) {
            $this->set('attrs.class', trim($this->get('attrs.class') . " Accordion--{$theme}"));
        }

        $this->set([
            'attrs.data-control' => 'accordion',
            'attrs.data-id'      => $this->getId(),
            'attrs.data-options' => [
                'multiple'  => $this->get('multiple'),
                'opened'    => $this->get('opened'),
                'triggered' => $this->get('triggered'),
            ],
        ]);

        $items = $this->get('items', []);
        if (!$items instanceof AccordionWalker) {
            $items = new AccordionWalker($items);
        }

        $parent = !is_null($this->get('parent', null)) ? (string) $this->get('parent', null) : null;

        $this->set('items', $items->setPartial($this)->setParent($parent)->build());

        return $this;
    }
}