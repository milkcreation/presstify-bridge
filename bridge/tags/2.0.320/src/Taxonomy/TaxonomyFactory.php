<?php declare(strict_types=1);

namespace tiFy\Taxonomy;

use LogicException;
use tiFy\Contracts\Taxonomy\{Taxonomy, TaxonomyFactory as TaxonomyFactoryContract};
use tiFy\Support\ParamsBag;
use WP_Taxonomy;

/**
 * @property-read string $label
 * @property-read object $labels
 * @property-read string $description
 * @property-read bool $public
 * @property-read bool $publicly_queryable
 * @property-read bool $hierarchical
 * @property-read bool $show_ui
 * @property-read bool $show_in_menu
 * @property-read bool $show_in_nav_menus
 * @property-read bool $show_tagcloud
 * @property-read bool show_in_quick_edit
 * @property-read bool $show_admin_column
 * @property-read bool|callable $meta_box_cb
 * @property-read callable $meta_box_sanitize_cb
 * @property-read array $object_type
 * @property-read object $cap
 * @property-read array|false $rewrite
 * @property-read string|false $query_var
 * @property-read callable $update_count_callback
 * @property-read bool $show_in_rest
 * @property-read string|bool $rest_base
 * @property-read string|bool  $rest_controller_class
 * @property-read bool $_builtin
 */
class TaxonomyFactory extends ParamsBag implements TaxonomyFactoryContract
{
    /**
     * Indicateur d'instanciation.
     * @var boolean
     */
    private $prepared = false;

    /**
     * Instance du gestionnaire de taxonomie.
     * @var Taxonomy
     */
    protected $manager;

    /**
     * Nom de qualification de l'élément.
     * @var string
     */
    protected $name = '';

    /**
     * Instance de la taxonomie Wordpress associée.
     * @return WP_Taxonomy|null
     */
    protected $wpTax;

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification de l'élément.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return void
     */
    public function __construct(string $name, array $attrs = [])
    {
        $this->name = $name;
        $this->set($attrs);
    }

    /**
     * @inheritDoc
     */
    public function __get($key)
    {
        return $this->wpTax->{$key} ?? parent::__get($key);
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            //'label'              => '',
            //'labels'             => '',
            'public'                => true,
            //'publicly_queryable'    => true,
            //'show_ui'            => true,
            //'show_in_menu'       => true,
            //'show_in_nav_menus'  => false,
            'show_in_rest'          => false,
            // @todo 'rest_base'          => ''
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            //'show_tagcloud'      => false,
            //'show_in_quick_edit' => false,
            'meta_box_cb'           => null,
            'show_admin_column'     => false,
            'description'           => '',
            'hierarchical'          => false,
            // @todo update_count_callback => ''
            'query_var'             => true,
            'rewrite'               => true,
            // @todo 'capabilities'       => [],
            'sort'                  => true
        ];
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function label(string $key, string $default = '') : string
    {
        return $this->get("labels.{$key}", $default);
    }

    /**
     * @inheritdoc
     */
    public function meta($key, bool $single = true): TaxonomyFactoryContract
    {
        $keys = is_array($key) ? $key : [$key => $single];

        foreach ($keys as $k => $v) {
            if (is_numeric($k)) {
                $k = $v;
                $v = $single;
            }
            $this->manager->meta()->register($this->getName(), $k, $v);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function parse(): TaxonomyFactoryContract
    {
        parent::parse();

        $labels = $this->get('labels', []);
        if (is_object($labels)) {
            $this->set('labels', get_object_vars($labels));
        }

        $this->set('label', $this->get('label', _x($this->getName(), 'taxonomy general name', 'tify')));

        $this->set('plural', $this->get('plural', $this->get('labels.name', $this->get('label'))));

        $this->set('singular', $this->get('singular', $this->get('labels.singular_name', $this->get('label'))));

        $this->set('gender', $this->get('gender', false));

        $labels = TaxonomyLabelsBag::createFromAttrs(array_merge([
            'singular' => $this->get('singular'),
            'plural'   => $this->get('plural'),
            'gender'   => $this->get('gender'),
        ], (array)$this->get('labels', [])), $this->get('label'));
        $this->set('labels', $labels->all());

        $this->set('publicly_queryable', $this->has('publicly_queryable')
            ? $this->get('publicly_queryable')
            : $this->get('public'));

        $this->set('show_ui', $this->has('show_ui') ? $this->get('show_ui') : $this->get('public'));

        $this->set('show_in_nav_menus', $this->has('show_in_nav_menus')
            ? $this->get('show_in_nav_menus')
            : $this->get('public'));

        $this->set('show_in_menu', $this->has('show_in_menu')
            ? $this->get('show_in_menu')
            : $this->get('show_ui'));

        $this->set('show_in_admin_bar', $this->has('show_in_admin_bar')
            ? $this->get('show_in_admin_bar')
            : $this->get('show_in_menu'));

        $this->set('show_tagcloud', $this->has('show_tagcloud')
            ? $this->get('show_tagcloud')
            : $this->get('show_ui'));

        $this->set('show_in_quick_edit', $this->has('show_in_quick_edit')
            ? $this->get('show_in_quick_edit')
            : $this->get('show_ui'));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function prepare(): TaxonomyFactoryContract
    {
        if (!$this->prepared) {
            if (!$this->manager instanceof Taxonomy) {
                throw new LogicException(sprintf(
                    __('Le gestionnaire [%s] devrait être défini avant de déclencher le démarrage', 'tify'),
                    Taxonomy::class
                ));
            }
            $this->parse();
            events()->trigger('taxonomy.factory.boot', [&$this]);
            $this->prepared = true;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setManager(Taxonomy $manager): TaxonomyFactoryContract
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Définition de l'instance de la taxonomie Wordpress associée.
     *
     * @param WP_Taxonomy $taxonomy
     *
     * @return static
     */
    public function setWpTaxonomy(WP_Taxonomy $taxonomy): TaxonomyFactoryContract
    {
        $this->wpTax = $taxonomy;

        return $this;
    }
}