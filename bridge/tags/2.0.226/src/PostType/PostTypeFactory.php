<?php declare(strict_types=1);

namespace tiFy\PostType;

use LogicException;
use tiFy\Contracts\PostType\PostTypeFactory as PostTypeFactoryContract;
use tiFy\Contracts\PostType\PostTypeManager;
use tiFy\Support\ParamsBag;

class PostTypeFactory extends ParamsBag implements PostTypeFactoryContract
{
    /**
     * Indicateur d'instanciation.
     * @var boolean
     */
    private $booted = false;

    /**
     * Instance du gestionnaire de taxonomie.
     * @var PostTypeManager
     */
    protected $manager;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification.
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
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        if (!$this->booted) {
            if (!$this->manager instanceof PostTypeManager) {
                throw new LogicException(sprintf(
                    __('Le gestionnaire %s devrait être défini avant de déclencher le démarrage', 'tify'),
                    PostTypeManager::class
                ));
            }
            $this->parse();
            events()->trigger('post-type.factory.boot', [&$this]);
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
            'description'           => '',
            'public'                => true,
            //'exclude_from_search'   => false,
            //'publicly_queryable'    => true,
            //'show_ui'               => true,
            //'show_in_nav_menus'     => true,
            //'show_in_menu'          => true,
            //'show_in_admin_bar'     => true,
            'menu_position'         => null,
            'menu_icon'             => null,
            'capability_type'       => 'post',
            // @todo capabilities   => [],
            'map_meta_cap'          => null,
            'hierarchical'          => false,
            'supports'              => ['title', 'editor'],
            // @todo 'register_meta_box_cb'  => '',
            'taxonomies'            => [],
            'has_archive'           => false,
            'rewrite'               => [
                'slug'       => $this->getName(),
                'with_front' => false,
                'feeds'      => true,
                'pages'      => true,
                'ep_mask'    => EP_PERMALINK,
            ],
            'permalink_epmask'      => EP_PERMALINK,
            'query_var'             => true,
            'can_export'            => true,
            'delete_with_user'      => null,
            'show_in_rest'          => false,
            'rest_base'             => $this->getName(),
            'rest_controller_class' => 'WP_REST_Posts_Controller'
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
    public function label(string $key, string $default = ''): string
    {
        return $this->get("labels.{$key}", $default);
    }

    /**
     * @inheritdoc
     */
    public function meta($key, bool $single = true): PostTypeFactoryContract
    {
        $keys = is_array($key) ? $key : [$key => $single];

        foreach ($keys as $k => $v) {
            if (is_numeric($k)) {
                $k = $v;
                $v = $single;
            }
            $this->manager->post_meta()->register($this->getName(), $k, $v);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function parse(): PostTypeFactoryContract
    {
        parent::parse();

        $labels = $this->get('labels', []);
        if (is_object($labels)) {
            $this->set('labels', get_object_vars($labels));
        }

        $this->set('label', $this->get('label', _x($this->getName(), 'post type general name', 'tify')));

        $this->set('plural', $this->get('plural',
            $this->get('labels.name', $this->get('label'))
        ));

        $this->set('singular', $this->get('singular',
            $this->get('labels.singular_name', $this->get('label'))
        ));

        $this->set('gender', $this->get('gender', false));

        $labels = PostTypeLabelsBag::createFromAttrs(array_merge([
            'singular' => $this->get('singular'),
            'plural'   => $this->get('plural'),
            'gender'   => $this->get('gender'),
        ], $this->get('labels', [])), $this->get('label'));
        $this->set('labels', $labels->all());

        $this->set('exclude_from_search', $this->has('exclude_from_search')
            ? $this->get('exclude_from_search')
            : !$this->get('public'));

        $this->set('publicly_queryable', $this->has('publicly_queryable')
            ? $this->get('publicly_queryable')
            : $this->get('public'));

        $this->set('show_ui', $this->has('show_ui')
            ? $this->get('show_ui')
            : $this->get('public'));

        $this->set('show_in_nav_menus', $this->has('show_in_nav_menus')
            ? $this->get('show_in_nav_menus')
            : $this->get('public'));

        $this->set('show_in_menu', $this->has('show_in_menu')
            ? $this->get('show_in_menu')
            : $this->get('show_ui'));

        $this->set('show_in_admin_bar', $this->has('show_in_admin_bar')
            ? $this->get('show_in_admin_bar')
            : $this->get('show_in_menu'));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setManager(PostTypeManager $manager): PostTypeFactoryContract
    {
        $this->manager = $manager;

        return $this;
    }
}