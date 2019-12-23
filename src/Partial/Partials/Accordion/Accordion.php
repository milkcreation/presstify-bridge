<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Accordion;

use tiFy\Contracts\Partial\{Accordion as AccordionContract, PartialFactory as PartialFactoryContract};
use tiFy\Partial\PartialFactory;

class Accordion extends PartialFactory implements AccordionContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var string $theme Theme d'affichage. light|dark.
     *      @var array|AccordionItem[]|AccordionWalkerItems Liste des éléments.
     *      @var mixed $opened Définition de la liste des éléments ouverts à l'initialisation.
     *      @var boolean $multiple Activation de l'ouverture multiple d'éléments.
     *      @var boolean $triggered Activation de la limite d'ouverture et de fermeture par le déclencheur de l'élement.
     * }
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
            'opened'    => null,
            'theme'     => 'light',
            'triggered' => false,
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialFactoryContract
    {
        parent::parse();

        if ($theme = $this->get('theme')) {
            $this->set('attrs.class', trim($this->get('attrs.class') . " Accordion--{$theme}"));
        }

        $this->set('attrs.data-control', 'accordion');

        $this->set('attrs.data-id', $this->getId());

        $this->set('attrs.data-options', [
            'multiple'  => $this->get('multiple'),
            'opened'    => $this->get('opened'),
            'triggered' => $this->get('triggered'),
        ]);

        $items = $this->get('items', []);
        if (!$items instanceof AccordionWalkerItems) {
            $items = new AccordionWalkerItems($items, $this->get('opened'));
        }

        $items->setPartial($this);

        $this->set('items', $items);

        return $this;
    }
}