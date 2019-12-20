<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Support\Collection;
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\ListTable\Contracts\{BulkAction, BulkActions as BulkActionsContract};
use tiFy\Support\Proxy\Field;

class BulkActions extends Collection implements BulkActionsContract
{
    use FactoryAwareTrait;

    /**
     * Compteur d'instance d'affichage.
     * @var int
     */
    protected static $displayed = 0;

    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

    /**
     * Liste des actions groupées.
     * @var array|BulkAction[]
     */
    protected $items = [];

    /**
     * Position de l'interface de navigation.
     * @var string
     */
    protected $which = 'top';

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->render();
    }

    /**
     * {@inheritDoc}
     *
     * @return array|BulkAction[]
     */
    public function all()
    {
        return parent::all();
    }

    /**
     * @inheritDoc
     */
    public function parse(array $bulk_actions = []): BulkActionsContract
    {
        if ($bulk_actions) {
            $this->items[-1] = $this->factory->resolve('bulk-actions.item', [
                -1,
                ['content' => __('Actions groupées', 'tify')],
                $this->factory
            ]);

            foreach ($bulk_actions as $name => $attrs) {
                if (is_numeric($name)) {
                    $name = (string)$attrs;
                    $attrs = [];
                } elseif (is_string($attrs)) {
                    $attrs = [
                        'value'   => $name,
                        'content' => $attrs
                    ];
                }

                $alias = $this->factory->bound("bulk-action.{$name}")
                    ? "bulk-action.{$name}"
                    : 'bulk-action.item';

                $this->items[$name] = $this->factory->resolve($alias, [$name, $attrs]);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $output = '';

        if ($choices = $this->all()) {
            $displayed = !self::$displayed++ ? '' : 2;

            $output .= Field::get('label', [
                'attrs'   => [
                    'for'   => 'bulk-action-selector-' . esc_attr($this->which),
                    'class' => 'screen-reader-text'
                ],
                'content' => __('Choix de l\'action', 'tify')
            ]);

            $output .= Field::get('select', [
                'name'    => "action{$displayed}",
                'attrs'   => [
                    'id' => 'bulk-action-selector-' . esc_attr($this->which)
                ],
                'choices' => $choices
            ]);

            $output .= Field::get('submit', [
                'attrs' => [
                    'id'    => "doaction{$displayed}",
                    'value' => __('Apply'),
                    'class' => 'button action'
                ]
            ]);
        }

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function which(string $which) : BulkActionsContract
    {
        $this->which = $which;

        return $this;
    }
}