<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\CurtainMenu;

use tiFy\Contracts\Partial\{CurtainMenu as CurtainMenuContract, PartialFactory as PartialFactoryContract};
use tiFy\Partial\PartialFactory;

class CurtainMenu extends PartialFactory implements CurtainMenuContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var array $items Liste des éléments.
     *      @var mixed $selected
     *      @var string $theme Theme d'affichage. light|dark.
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
            'selected'  => null,
            'theme'     => 'light',
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialFactoryContract
    {
        parent::parse();

        if ($theme = $this->get('theme')) {
            $this->set('attrs.class', trim($this->get('attrs.class') . " CurtainMenu--{$theme}"));
        }

        $this->set('attrs.data-control', 'curtain-menu');

        $this->set('attrs.data-id', $this->getId());

        $this->set('attrs.data-options', []);

        $this->parseItems();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseItems(): PartialFactoryContract
    {
        $items = $this->get('items', []);
        if (!$items instanceof CurtainMenuItems) {
            $items = new CurtainMenuItems($items, $this->get('selected'));
        }
        $this->set('items', $items->prepare($this));

        return $this;
    }
}