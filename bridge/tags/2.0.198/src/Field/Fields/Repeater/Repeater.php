<?php declare(strict_types=1);

namespace tiFy\Field\Fields\Repeater;

use tiFy\Contracts\Field\{FieldFactory as FieldFactoryContract, Repeater as RepeaterContract};
use tiFy\Field\FieldFactory;
use tiFy\Support\{Arr, Proxy\Request};

class Repeater extends FieldFactory implements RepeaterContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var string $name Clé d'indice de la valeur de soumission du champ.
     *      @var string $value Valeur courante de soumission du champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var array $ajax Liste des arguments de requête de récupération des éléments via Ajax.
     *      @var array $button Liste des attributs de configuration du bouton d'ajout d'un élément.
     *      @var int $max Nombre maximum de valeur pouvant être ajoutées. -1 par défaut, pas de limite.
     *      @var boolean $removable Activation du déclencheur de suppression des éléments.
     *      @var bool|array $sortable Activation de l'ordonnacemment des éléments|Liste des attributs de configuration.
     *                                @see http://api.jqueryui.com/sortable/
     *      @var array $args Arguments complémentaires porté par la requête Ajax.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'     => [],
            'after'     => '',
            'before'    => '',
            'name'      => '',
            'value'     => '',
            'viewer'    => [],
            'ajax'      => [],
            'args'      => [],
            'button'    => [],
            'max'       => -1,
            'removable' => true,
            'sortable'  => true,
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldFactoryContract
    {
        parent::parse();

        $this->set('attrs.class', trim(sprintf($this->get('attrs.class', '%s'), ' FieldRepeater')));

        $this->set('attrs.data-id', $this->getId());

        $this->set('attrs.data-control', 'repeater');

        $button = $this->get('button');
        $button = is_string($button) ? ['content' => $button] : $button;

        $button = array_merge([
            'tag' => 'a',
            'content' => __('Ajouter un élément', 'tify')
        ], $button);
        $this->set('button', $button);

        if(($this->get('button.tag') === 'a') && !$this->get('button.attrs.href')) {
            $this->set('button.attrs.href', "#{$this->get('attrs.id')}");
        }
        if (! $this->get('button.attrs.class')) {
            $this->set('button.attrs.class', 'FieldRepeater-buttonAdd' . (is_admin() ? ' button-secondary' : ''));
        }
        $this->set('button.attrs.data-control', 'repeater.trigger');

        if ($sortable = $this->get('sortable')) {
            if (!is_array($sortable)) {
                $sortable = [];
            }
            $this->set('sortable', array_merge([
                'placeholder' => 'FieldRepeater-itemPlaceholder',
                'axis'        => 'y'
            ], $sortable));
            $this->set('order', '__order_' . $this->getName());
        }

        $this->set('attrs.data-options', [
            'ajax'      => array_merge([
                'url'    => admin_url('admin-ajax.php', 'relative'),
                'data'   => [
                    '_id'         => $this->getId(),
                    '_viewer'     => $this->get('viewer'),
                    'args'        => $this->get('args', []),
                    'max'         => $this->get('max'),
                    'name'        => $this->getName(),
                    'order'       => $this->get('order'),
                ],
                'method' => 'post',
            ], $this->get('ajax', [])),
            'removable' => $this->get('removable'),
            'sortable'  => $this->get('sortable'),
        ]);

        $this->set('value', array_values(Arr::wrap($this->get('value', []))));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseDefaults(): FieldFactoryContract
    {
        $this->parseViewer();

        return $this;
    }

    /**
     * Génération de la réponse HTTP via une requête XHR.
     *
     * @return array
     */
    public function xhrResponse(): array
    {
        $max = Request::input('max', -1);
        $this
            ->set('name', Request::input('name', ''))
            ->set('viewer', Request::input('_viewer', []))
            ->parse();

        if (($max > 0) && (Request::input('count', 0) >= $max)) {
            return [
                'success' => false,
                'data' => __('Nombre de valeur maximum atteinte', 'tify')
            ];
        } else {
            return [
                'success' => true,
                'data'    => (string)$this->viewer('item-wrap', array_merge(Request::all(), ['value' => '']))
            ];
        }
    }
}