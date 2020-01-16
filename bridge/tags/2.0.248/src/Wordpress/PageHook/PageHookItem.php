<?php declare(strict_types=1);

namespace tiFy\Wordpress\PageHook;

use Closure;
use tiFy\Contracts\Partial\BreadcrumbCollection as BaseBreadcrumbCollection;
use tiFy\Contracts\Routing\Route;
use tiFy\Support\ParamsBag;
use tiFy\Wordpress\Contracts\{PageHookItem as PageHookItemContract,
    Partial\BreadcrumbCollection,
    Query\QueryPost as QueryPostContract
};
use tiFy\Wordpress\Query\QueryPost;
use WP_Admin_Bar;
use WP_Post;
use WP_Post_Type;
use WP_Query;
use WP_Term;

class PageHookItem extends ParamsBag implements PageHookItemContract
{
    /**
     * Indicateur d'instance de l'accroche en tant que page courante.
     * @var bool|null
     */
    protected $globalCurrent;

    /**
     * Indicateur d'instance de l'accroche en tant que page parente.
     * @var bool|null
     */
    protected $globalAncestor;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Instance du post associé.
     * @var QueryPost
     */
    protected $post;

    /**
     * Instance de la route associée.
     * @var Route
     */
    protected $route;

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

        $this->set($attrs)->parse();

        add_filter('display_post_states', function (array $post_states, WP_Post $post) {
            if (($label = $this->get('display_post_states')) && $this->is($post)) {
                if (!is_string($label)) {
                    $label = $this->getTitle();
                }
                $post_states[] = $label;
            }
            return $post_states;
        }, 10, 2);

        add_action('edit_form_top', function (WP_Post $post) {
            if (($label = $this->get('edit_form_notice')) && $this->is($post)) {
                if (!is_string($label)) {
                    $label = sprintf(__('Vous éditez actuellement : %s.', 'tify'), $this->getTitle());
                }
                echo "<div class=\"notice notice-info inline\">\n\t<p>{$label}</p>\n</div>";
            }
        });

        add_action('init', function () {
            if (($rewrite = $this->get('rewrite')) && $this->exists()) {
                if (preg_match('/(.*)@post_type/', $rewrite, $matches) && post_type_exists($matches[1])) {
                    global $wp_rewrite, $wp_post_types;

                    $post_type = $matches[1];

                    if (isset($wp_post_types[$post_type])) {
                        /** @var WP_Post_Type $obj */
                        $obj = &$wp_post_types[$post_type];
                        $obj->has_archive = true;
                        $obj->rewrite = true;
                        $pbase = $wp_rewrite->pagination_base;
                        $hookname = $this->getName();

                        $obj->remove_rewrite_rules();

                        add_rewrite_rule(
                            "{$this->getPath()}/([^/]+)/?$",
                            'index.php?post_type=' . $post_type . '&name=$matches[1]' .
                            '&hookname=' . $hookname,
                            'top'
                        );

                        if ($this->post()->typeIn(['page'])) {
                            add_rewrite_rule(
                                "{$this->getPath()}/{$pbase}/([0-9]{1,})/?$",
                                'index.php?page_id=' . $this->post()->getId() . '&paged=$matches[1]' .
                                '&hookname=' . $hookname,
                                'top'
                            );
                        } else {
                            add_rewrite_rule(
                                "{$this->getPath()}/{$pbase}/([0-9]{1,})/?$",
                                'index.php?p=' . $this->post()->getId() . '&post_type=' . $this->post()->getType() .
                                '&paged=$matches[1]&hookname=' . $hookname,
                                'top'
                            );
                        }

                        add_filter('post_type_link', function (string $link, WP_Post $post) use ($post_type) {
                            if ($post->post_type === $post_type) {
                                return rtrim($this->post()->getPermalink(), '/') . '/' . $post->post_name;
                            }
                            return $link;
                        }, 999999, 2);

                        add_action('save_post', function (int $post_id) {
                            $post = get_post($post_id);
                            if ($this->is($post)) {
                                flush_rewrite_rules();
                            }
                        }, 999999);

                        add_action('admin_bar_menu', function (WP_Admin_Bar $wp_admin_bar) {
                            if (!is_admin()) {
                                if ($this->is()) {
                                    $wp_admin_bar->add_menu([
                                        'id'    => 'edit',
                                        'title' => $this->post()->getType()->label('edit_item'),
                                        'href'  => $this->post()->getEditLink(),
                                    ]);
                                }
                            }
                        }, 90);
                    }
                } elseif (preg_match('/(.*)@taxonomy/', $rewrite, $matches) && taxonomy_exists($matches[1])) {
                    global $wp_rewrite, $wp_taxonomies;

                    $taxonomy = $matches[1];

                    if (isset($wp_taxonomies[$taxonomy])) {
                        $wp_taxonomies[$taxonomy]->rewrite = false;
                        $pbase = $wp_rewrite->pagination_base;
                        $hookname = $this->getName();

                        add_rewrite_rule(
                            "{$this->getPath()}/([^/]+)/?$",
                            'index.php?taxonomy=' . $taxonomy . '&term=$matches[1]&hookname=' . $hookname,
                            'top'
                        );

                        add_rewrite_rule(
                            "{$this->getPath()}/([^/]+)/{$pbase}/([0-9]{1,})/?$",
                            'index.php?taxonomy=' . $taxonomy . '&term=$matches[1]&paged=$matches[2]&hookname=' .
                            $hookname,
                            'top'
                        );

                        add_filter('term_link', function (string $link, WP_Term $term, string $tax) use ($taxonomy) {
                            if ($tax === $taxonomy) {
                                return rtrim($this->post()->getPermalink(), '/') . '/' . $term->slug;
                            }
                            return $link;
                        }, 999999, 3);
                    }
                }
            }
        }, 999999);

        add_action('after_setup_theme', function () {
            if (($route = $this->get('route')) && is_callable($route) && $this->exists()) {
                $this->route = router()->get($this->getPath() . '[/page/{page:\d+}]', $route);
            }
        }, 25);

        add_action('pre_get_posts', function (WP_Query &$wp_query) {
            if ($wp_query->is_main_query() && ($query_args = $this->get('wp_query'))) {
                if ($this->is()) {
                    if (is_array($query_args)) {
                        if ($paged = $wp_query->get('paged')) {
                            $query_args = array_merge(['paged' => $paged], $query_args);
                        }

                        $wp_query->parse_query($query_args);
                    } else {
                        $wp_query->parse_query($wp_query->query);
                    }
                }
            }
        });

        events()->listen('partial.breadcrumb.fetch', function (BaseBreadcrumbCollection $bc) {
            if ($bc instanceof BreadcrumbCollection) {
                if ($this->is() || $this->isAncestor()) {
                    $hookid = $this->post()->getId();

                    $bc->addRoot(null, true);

                    if ($acs = $bc->getAncestorsRender($hookid)) {
                        array_walk($acs, function ($render) use ($bc) {
                            $bc->add($render);
                        });
                    }

                    if ($this->is()) {
                        if ($pr = $bc->getPostRender($hookid, false)) {
                            $bc->add($pr);
                        }
                    } else {
                        if ($pr = $bc->getPostRender($hookid, true)) {
                            $bc->add($pr);
                        }

                        if (is_tax()) {
                            $id = get_queried_object_id();
                        } elseif (is_single()) {
                            $id = get_the_ID();
                            if ($acs = $bc->getAncestorsRender($id)) {
                                array_walk($acs, function ($render) use ($bc) {
                                    $bc->add($render);
                                });
                            }

                            if ($pr = $bc->getPostRender($id, false)) {
                                $bc->add($pr);
                            }
                        }
                    }
                }
            }
        }, 100);
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'admin'               => true,
            'id'                  => 0,
            'desc'                => '',
            'display_post_states' => true,
            'edit_form_notice'    => true,
            'listorder'           => 'menu_order, title',
            'object_type'         => 'post',
            'object_name'         => 'page',
            'option_name'         => "page_hook_{$this->name}",
            // Association de contenus && Réécriture des urls des contenus
            // ex. books@post_type || comics@taxonomy
            'rewrite'             => null,
            'route'               => false,
            'show_option_none'    => __('Aucune page choisie', 'tify'),
            'title'               => $this->name,
            // Requête de la page d'affichage
            'wp_query'            => true,
        ];
    }

    /**
     * @inheritDoc
     */
    public function exists(): bool
    {
        return $this->post() instanceof QueryPost;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        $desc = $this->get('desc', '');

        return $desc instanceof Closure ? call_user_func($desc) : $desc;
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
    public function getPath(): string
    {
        return $this->exists() ? $this->post()->getPath() : '';
    }

    /**
     * @inheritDoc
     */
    public function getObjectType(): string
    {
        return $this->get('object_type');
    }

    /**
     * @inheritDoc
     */
    public function getObjectName(): string
    {
        return $this->get('object_name');
    }

    /**
     * @inheritDoc
     */
    public function getOptionName(): string
    {
        return $this->get('option_name');
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        $title = $this->get('title', '');

        return $title instanceof Closure ? call_user_func($title) : $title;
    }

    /**
     * @inheritDoc
     */
    public function is(?WP_Post $post = null): bool
    {
        if (!$post && ($route = $this->route())) {
            return router()->current() === $route;
        } elseif ($this->exists()) {
            if ($post) {
                return $this->post()->getId() === intval($post->ID);
            } else {
                if (!is_null($this->globalCurrent)) {
                    return $this->globalCurrent;
                } else {
                    /** @var WP_Query $wp_query */
                    global $wp_query;

                    if ($wp_query->is_main_query()) {
                        if ($pagename = $wp_query->get('pagename', '')) {
                            $this->globalCurrent = ($this->getPath() === $pagename);
                        } elseif ($page_id = $wp_query->get('page_id', 0)) {
                            $this->globalCurrent = ($this->post()->getId() === intval($page_id));
                        } else {
                            $this->globalCurrent = did_action('parse_query') ? false : null;
                        }

                        return $this->globalCurrent;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function isAncestor(): bool
    {
        if ($this->exists()) {
            if (!is_null($this->globalAncestor)) {
                return $this->globalAncestor;
            } else {
                /** @var WP_Query $wp_query */
                global $wp_query;

                if ($wp_query->is_main_query()) {
                    if ($hookname = $wp_query->get('hookname', '')) {
                        $this->globalAncestor = ($this->getName() === $hookname);
                    } else {
                        $this->globalAncestor = did_action('parse_query') ? false : null;
                    }

                    return $this->globalAncestor;
                }
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function post(): ?QueryPostContract
    {
        if (is_null($this->post)) {
            if (!$post_id = $this->get('id')) {
                $this->set('id', $post_id = (int)get_option($this->get('option_name'), 0));
            }

            $this->post = ($post_id && ($post = get_post($post_id))) ? new QueryPost($post) : null;
        }

        return $this->post;
    }

    /**
     * @inheritDoc
     */
    public function route(): ?Route
    {
        return $this->route instanceof Route ? $this->route : null;
    }
}