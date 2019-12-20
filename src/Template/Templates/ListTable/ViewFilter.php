<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Support\{ParamsBag, Proxy\Partial};
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\ListTable\Contracts\ViewFilter as ViewFilterContract;

class ViewFilter extends ParamsBag implements ViewFilterContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->render();
    }

    /**
     * Liste des attributs de configuration par défaut.
     * @return array {
     *      @var string $content Contenu du lien de vue filtrée (chaîne de caractère ou éléments HTML).
     *      @var array $attrs Liste des attributs de balise HTML.
     *      @var array $query_args Tableau associatif des arguments passés en requête dans l'url du lien de vue filtrée
     *      @var array $remove_query_args Tableau indexé des arguments supprimés de l'url de requête du lien de vue
     *                                    filtrée
     *      @var int $count_items Nombre d'élément correspondant à la vue filtrée
     *      @var bool $current Définie si la vue courante correspond à la vue filtrée
     *      @var bool $hide_empty Masque le lien si aucun élément ne correspond à la vue filtrée
     *      @var bool|string $show_count Affiche le nombre d'éléments correspondant dans le lien de la vue filtrée
     *                                   false|true|'(%d)' où %d correspond au nombre d'éléments
     * }
     */
    public function defaults()
    {
        return [
            'content'           => '',
            'attrs'             => [],
            'query_args'        => [],
            'remove_query_args' => [], //['action', 'action2', 'filter_action', '_wp_nonce', '_wp_referer']
            'count_items'       => 0,
            'current'           => false,
            'hide_empty'        => false,
            'show_count'        => false,
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse()
    {
        parent::parse();

        if (!$this->get('attrs.href')) {
            $this->set('attrs.href', $this->factory->param('page_url', $this->factory->request()->fullUrl()));
        }

        if ($query_args = $this->get('query_args', [])) {
            $this->set('attrs.href', add_query_arg($query_args, $this->get('attrs.href')));
        }

        if ($remove_query_args = $this->get('remove_query_args', [])) {
            $this->set('attrs.href', remove_query_arg($remove_query_args, $this->get('attrs.href')));
        }

        if ($this->get('current')) {
            $this->set('attrs.class', (($class = $this->get('attrs.class')) ? "{$class} current" : 'current'));
        }

        if (!$this->get('content')) {
            $this->set('content', $this->name);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        if ($this->get('hide_empty') && !$this->get('count_items', 0)) {
            return '';
        }

        return (string)Partial::get('tag', [
            'tag'     => 'a',
            'attrs'   => $this->get('attrs', []),
            'content' => $this->get('content'),
            'after'   => $this->get('show_count')
                ? " <span class=\"count\">(" . $this->get('count_items') . ")</span>"
                : ''
        ]);
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): ViewFilterContract
    {
        $this->name = $name;

        return $this;
    }
}