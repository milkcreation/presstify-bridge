<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\CurtainMenu;

use tiFy\Contracts\Partial\{CurtainMenu as CurtainMenuContract, PartialDriver as PartialDriverContract};
use tiFy\Partial\PartialDriver;

class CurtainMenu extends PartialDriver implements CurtainMenuContract
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
    public function parse(): PartialDriverContract
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
    public function parseItems(): PartialDriverContract
    {
        $items = $this->get('items', []);
        if (!$items instanceof CurtainMenuItems) {
            $items = new CurtainMenuItems($items, $this->get('selected'));
        }
        $this->set('items', $items->prepare($this));

        return $this;
    }
}