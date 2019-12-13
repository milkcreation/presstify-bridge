<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Tab;

use tiFy\Contracts\Partial\{PartialFactory as PartialFactoryContract, Tab as TabContract};
use tiFy\Contracts\Partial\TabItems as TabItemsContract;
use tiFy\Partial\PartialFactory;

class Tab extends PartialFactory implements TabContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var string $active Nom de qualification de l'élément actif.
     *      @var array $items {
     *          Liste des onglets de navigation.
     *
     *          @var string $name Nom de qualification.
     *          @var string $parent Nom de qualification de l'élément parent.
     *          @var string|callable $content
     *          @var int $position Ordre d'affichage dans le
     *      }
     *      @var array $rotation Rotation des styles d'onglet.
     */
    public function defaults(): array
    {
        return [
            'attrs'    => [],
            'after'    => '',
            'before'   => '',
            'viewer'   => [],
            'active'   => null,
            'items'    => [],
            'rotation' => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function display(): string
    {
        /* @var TabItemsContract $items */
        $items = $this->get('items');

        return (string)$this->viewer('index', ['attrs' => $this->get('attrs', []), 'items' => $items->getGrouped()]);
    }

    /**
     * @inheritDoc
     */
    public function getTabStyle(int $depth = 0)
    {
        return $this->get("rotation.{$depth}") ? : 'default';
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialFactoryContract
    {
        parent::parse();

        $this->set('attrs.data-control', 'tab');

        $items = $this->get('items', []);
        if (!$items instanceof TabItemsContract) {
            $items = new TabItems($items, $this->get('active'));
        }
        /* @var TabItemsContract $items */
        $this->set('items', $items->prepare($this));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function viewer($view = null, $data = [])
    {
        if (is_null($this->viewer)) {
            $this->viewer = app()->get('partial.viewer', [$this]);
            $this->viewer->setController(TabView::class);
        }
        return parent::viewer($view, $data);
    }

    /**
     * @inheritDoc
     */
    public function xhrSetTab()
    {
        return ['success' => true];
    }
}