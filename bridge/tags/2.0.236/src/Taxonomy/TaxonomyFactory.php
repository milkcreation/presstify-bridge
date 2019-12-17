<?php declare(strict_types=1);

namespace tiFy\Taxonomy;

use LogicException;
use tiFy\Contracts\Taxonomy\TaxonomyFactory as TaxonomyFactoryContract;
use tiFy\Contracts\Taxonomy\TaxonomyManager;
use tiFy\Support\ParamsBag;

class TaxonomyFactory extends ParamsBag implements TaxonomyFactoryContract
{
    /**
     * Indicateur d'instanciation.
     * @var boolean
     */
    private $booted = false;

    /**
     * Instance du gestionnaire de taxonomie.
     * @var TaxonomyManager
     */
    protected $manager;

    /**
     * Nom de qualification de l'élément.
     * @var string
     */
    protected $name = '';

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification de l'élément.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return void
     */
    public function __construct($name, $attrs = [])
    {
        $this->name = $name;
        $this->set($attrs);
    }

    /**
     * @inheritdoc
     */
    public function boot(): void
    {
        if (!$this->booted) {
            if (!$this->manager instanceof TaxonomyManager) {
                throw new LogicException(sprintf(
                    __('Le gestionnaire %s devrait être défini avant de déclencher le démarrage', 'tify'),
                    TaxonomyManager::class
                ));
            }
            $this->parse();
            events()->trigger('taxonomy.factory.boot', [&$this]);
            $this->booted = true;
        }
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
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
            $this->manager->term_meta()->register($this->getName(), $k, $v);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function parse(): TaxonomyFactoryContract
    {
        parent::parse();

        $this->set('label', $this->get('label', _x($this->getName(), 'taxonomy general name', 'tify')));

        $this->set('plural', $this->get('plural', $this->get('label')));

        $this->set('singular', $this->get('singular', $this->get('label')));

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
     * @inheritdoc
     */
    public function setManager(TaxonomyManager $manager): TaxonomyFactoryContract
    {
        $this->manager = $manager;

        return $this;
    }
}