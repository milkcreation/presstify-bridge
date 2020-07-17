<?php declare(strict_types=1);

namespace tiFy\PostType;

use LogicException;
use tiFy\Contracts\PostType\{PostType, PostTypeFactory as PostTypeFactoryContract};
use tiFy\Support\ParamsBag;
use WP_Post_Type;

/**
 * @property-read string $label
 * @property-read object $labels
 * @property-read string $description
 * @property-read bool $public
 * @property-read bool $hierarchical
 * @property-read bool $exclude_from_search
 * @property-read bool $publicly_queryable
 * @property-read bool $show_ui
 * @property-read bool $show_in_menu
 * @property-read bool $show_in_nav_menus
 * @property-read bool $show_in_admin_bar
 * @property-read int $menu_position
 * @property-read string $menu_icon
 * @property-read string $capability_type
 * @property-read bool $map_meta_cap
 * @property-read string $register_meta_box_cb
 * @property-read array $taxonomies
 * @property-read bool|string $has_archive
 * @property-read string|bool $query_var
 * @property-read bool $can_export
 * @property-read bool $delete_with_user
 * @property-read bool $_builtin
 * @property-read string $_edit_link
 * @property-read object $cap
 * @property-read array|false $rewrite
 * @property-read array|bool $supports
 * @property-read bool $show_in_rest
 * @property-read string|bool $rest_base
 * @property-read string|bool $rest_controller_class
 * @property-read \WP_REST_Controller $rest_controller
 */
class PostTypeFactory extends ParamsBag implements PostTypeFactoryContract
{
    /**
     * Indicateur d'instanciation.
     * @var boolean
     */
    private $prepared = false;

    /**
     * Instance du gestionnaire de taxonomie.
     * @var PostType
     */
    protected $manager;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Instance du type de post Wordpress associé.
     * @return WP_Post_Type|null
     */
    protected $wpPostType;

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification.
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
        return $this->wpPost->{$key} ?? parent::__get($key);
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->getName();
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
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function label(string $key, string $default = ''): string
    {
        return $this->get("labels.{$key}", $default);
    }

    /**
     * @inheritDoc
     */
    public function meta($key, bool $single = true): PostTypeFactoryContract
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
     * @inheritDoc
     */
    public function parse(): PostTypeFactoryContract
    {
        parent::parse();

        $labels = $this->get('labels', []);
        if (is_object($labels)) {
            $this->set('labels', get_object_vars($labels));
        }

        $this->set('label', $this->get('label', _x($this->getName(), 'post type general name', 'tify')));

        $this->set('plural', $this->get('plural', $this->get('labels.name', $this->get('label'))));

        $this->set('singular', $this->get('singular', $this->get('labels.singular_name', $this->get('label'))));

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
     * @inheritDoc
     */
    public function prepare(): PostTypeFactoryContract
    {
        if (!$this->prepared) {
            if (!$this->manager instanceof PostType) {
                throw new LogicException(sprintf(
                    __('Le gestionnaire [%s] devrait être défini avant de déclencher le démarrage', 'tify'),
                    PostType::class
                ));
            }

            $this->parse();
            events()->trigger('post-type.factory.boot', [&$this]);
            $this->prepared = true;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setManager(PostType $manager): PostTypeFactoryContract
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setWpPostType(WP_Post_Type $post_type): PostTypeFactoryContract
    {
        $this->wpPostType = $post_type;

        return $this;
    }
}