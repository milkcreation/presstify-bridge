<?php declare(strict_types=1);

namespace tiFy\Wordpress\PageHook;

use Closure;
use tiFy\Contracts\Partial\BreadcrumbCollection as BaseBreadcrumbCollection;
use tiFy\Support\{ParamsBag, Proxy\Router};
use tiFy\Wordpress\Contracts\{PageHookItem as PageHookItemContract,
    Partial\BreadcrumbCollection,
    Query\QueryPost as QueryPostContract
};
use tiFy\Wordpress\Query\QueryPost;
use WP_Admin_Bar;
use WP_Post;
use WP_Query;
use WP_Term;

class PageHookItem extends ParamsBag implements PageHookItemContract
{
    /**
     * Indicateur d'initialisation.
     * @var bool
     */
    private $built = false;

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
     * @var string
     */
    protected $objectType = '';

    /**
     * @var string
     */
    protected $objectName = '';

    /**
     * Instance du post associé.
     * @var QueryPost
     */
    protected $post;

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

        $this->set($attrs)->build();
    }

    /**
     * Initialisation.
     *
     * @return static
     */
    private function build(): PageHookItemContract
    {
        if (!$this->built) {
            $this->parse();

            add_action('pre_get_posts', function (WP_Query $wp_query) {
                if (!is_admin() && ($rewrite = $this->get('rewrite') ?: '') && $wp_query->is_main_query()) {
                    if (preg_match('/(.*)@post_type/', $rewrite, $matches) && post_type_exists($matches[1])) {
                        if ($wp_query->is_post_type_archive($matches[1])) {
                            $wp_query->set('hookname', $this->getName());
                        } elseif ($wp_query->is_single() && ($wp_query->get('post_type') === $matches[1])) {
                            $wp_query->set('hookname', $this->getName());
                        }
                    } elseif (preg_match('/(.*)@taxonomy/', $rewrite, $matches) && taxonomy_exists($matches[1])) {
                        if (
                            $wp_query->is_tax($matches[1]) ||
                            (($matches[1] === 'category') && $wp_query->is_category()) ||
                            (($matches[1] === 'post_tag') && $wp_query->is_tag())
                        ) {
                            $wp_query->set('hookname', $this->getName());
                        }
                    }
                }
            }, 0);

            add_action('pre_get_posts', function (WP_Query $wp_query) {
                if (!is_admin() && $wp_query->is_main_query() /*&& !$this->get('rewrite')*/) {
                    if ($this->is()) {
                        if ($query_args = $this->get('wp_query')) {
                            if (is_array($query_args)) {
                                if ($paged = $wp_query->get('paged')) {
                                    $query_args = array_merge(['paged' => $paged], $query_args);
                                }
                                $wp_query->parse_query($query_args);
                            } else {
                                $wp_query->parse_query($wp_query->query);
                            }
                        } elseif (in_array(Router::currentRouteName(), $this->get('routes', []))) {
                            $wp_query->parse_query(['page_id' => $this->post()->getId()]);
                        }
                    }
                }
            });

            add_action('init', function () {
                register_setting('tify_options', $this->getOptionName());

                if (!$this->exists()) {
                    return;
                }

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

                $rewrite = $this->get('rewrite') ?: '';

                $post_type = preg_match('/(.*)@post_type/', $rewrite, $matches) ? $matches[1] : null;
                if (($post_type === 'post') && ($id = get_option('page_for_posts'))) {
                    $this->set(compact('id'));
                }

                if (preg_match('/(.*)@post_type/', $rewrite, $matches) && post_type_exists($post_type)) {
                    global $wp_post_types;

                    if (isset($wp_post_types[$post_type])) {
                        $this->objectType = 'post_type';
                        $this->objectName = $post_type;

                        $obj = &$wp_post_types[$post_type];
                        $obj->has_archive = true;
                        $obj->remove_rewrite_rules();

                        if (!is_array($obj->rewrite)) {
                            $obj->rewrite = [];
                        }

                        $obj->rewrite['slug'] = $this->getPath();

                        if (!isset($obj->rewrite['with_front'])) {
                            $obj->rewrite['with_front'] = true;
                        }

                        if (!isset($obj->rewrite['pages'])) {
                            $obj->rewrite['pages'] = true;
                        }

                        if (!isset($obj->rewrite['feeds']) || !$obj->has_archive) {
                            $obj->rewrite['feeds'] = (bool)$obj->has_archive;
                        }

                        if (!isset($obj->rewrite['ep_mask'])) {
                            if (isset($obj->permalink_epmask)) {
                                $obj->rewrite['ep_mask'] = $obj->permalink_epmask;
                            } else {
                                $obj->rewrite['ep_mask'] = EP_PERMALINK;
                            }
                        }

                        $obj->add_rewrite_rules();

                        /**
                         * Ancien.
                         * @todo suppr.
                         */
                        /*
                        global $wp_rewrite;

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
                        */

                        add_action('save_post', function (int $post_id) {
                            $post = get_post($post_id);

                            if ($this->is($post)) {
                                if (get_option('page_for_posts') == $post_id) {
                                    update_option('permalink_structure',
                                        '/' . rtrim(ltrim($this->post()->getPath(), '/'), '/') . '/%postname%'
                                    );
                                }

                                flush_rewrite_rules();
                            }
                        }, 999999);

                        add_action('admin_bar_menu', function (WP_Admin_Bar $wp_admin_bar) {
                            if (!is_admin()) {
                                if ($this->is()) {
                                    $wp_admin_bar->add_menu([
                                        'id'    => 'edit',
                                        'title' => $this->post()->getType()->label('edit_item'),
                                        'href'  => $this->post()->getEditUrl(),
                                    ]);
                                }
                            }
                        }, 90);
                    }
                } elseif (preg_match('/(.*)@taxonomy/', $rewrite, $matches) && taxonomy_exists($matches[1])) {
                    global $wp_taxonomies;

                    $taxonomy = $matches[1];

                    if (isset($wp_taxonomies[$taxonomy])) {
                        $this->objectType = 'taxonomy';
                        $this->objectName = $taxonomy;

                        $obj = &$wp_taxonomies[$taxonomy];
                        $obj->remove_rewrite_rules();

                        /**
                         * @internal Ancienne version >> Fonctionnelle.
                         */
                        $obj->rewrite = false;

                        global $wp_rewrite;

                        $pbase = $wp_rewrite->pagination_base;
                        $hookname = $this->getName();

                        if ($obj->hierarchical) {
                            $obj->rewrite = [
                                'slug'         => $this->getPath(),
                                'with_front'   => false,
                                'hierarchical' => true,
                            ];
                            $obj->add_rewrite_rules();
                        } else {
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
                        }

                        add_filter('term_link',
                            function (string $link, WP_Term $term, string $tax) use ($taxonomy) {
                                if ($tax === $taxonomy) {
                                    // @var WP_Taxonomy[] $wp_taxonomies
                                    global $wp_taxonomies;

                                    $base_url = rtrim($this->post()->getPermalink(), '/') . '/';
                                    $slug = $term->slug;

                                    if ($wp_taxonomies[$tax]->hierarchical) {
                                        $hierarchical_slugs = [];
                                        $ancestors = get_ancestors($term->term_id, $taxonomy, 'taxonomy');
                                        foreach ((array)$ancestors as $ancestor) {
                                            $ancestor_term = get_term($ancestor, $taxonomy);
                                            $hierarchical_slugs[] = $ancestor_term->slug;
                                        }
                                        $hierarchical_slugs = array_reverse($hierarchical_slugs);
                                        $hierarchical_slugs[] = $slug;

                                        return $base_url . implode('/', $hierarchical_slugs);
                                    } else {
                                        return $base_url . $slug;
                                    }
                                }
                                return $link;
                            }, 999999, 3);
                        /**/

                        /**
                         * @internal  Nouvelle version >> Ne permet pas de positionner la règle de réécriture en top.
                         * !!GARDER!!
                         * /
                         * if (!is_array($obj->rewrite)) {
                         * $obj->rewrite = [];
                         * }
                         *
                         * $obj->rewrite = array_merge([
                         * 'with_front'   => true,
                         * 'hierarchical' => false,
                         * 'ep_mask'      => EP_NONE,
                         * ], $obj->rewrite);
                         *
                         * $obj->rewrite['slug'] = $this->getPath();
                         *
                         * $obj->add_rewrite_rules();
                         * /**/
                    }
                }
            }, 999999);

            events()->listen('partial.breadcrumb.fetch', function (BaseBreadcrumbCollection $bc, $e) {
                if ($bc instanceof BreadcrumbCollection) {
                    if (in_array(Router::currentRouteName(), $this->get('routes', []))) {
                        $bc->clear();
                        $bc->addRoot(null, true);
                        $hookid = $this->post()->getId();

                        if ($acs = $bc->getPostAncestorsRender($hookid)) {
                            array_walk($acs, function ($render) use ($bc) {
                                $bc->add($render);
                            });
                        }

                        if ($pr = $bc->getPostRender($hookid, false)) {
                            $bc->add($pr);
                        }

                        $e->stopPropagation();
                    } elseif ($this->is() || $this->isAncestor()) {
                        $bc->clear();

                        $hookid = $this->post()->getId();
                        $paged = is_paged();

                        $bc->addRoot(null, true);

                        if ($acs = $bc->getPostAncestorsRender($hookid)) {
                            array_walk($acs, function ($render) use ($bc) {
                                $bc->add($render);
                            });
                        }

                        if ($this->is()) {
                            if ($pr = $bc->getPostRender($hookid, $paged)) {
                                $bc->add($pr);
                            }
                        } else {
                            if ($pr = $bc->getPostRender($hookid, true)) {
                                $bc->add($pr);
                            }

                            if (is_tax() || is_tag() || is_category()) {
                                $id = get_queried_object_id();

                                if ($acs = $bc->getTermAncestorsRender($id)) {
                                    array_walk($acs, function ($render) use ($bc) {
                                        $bc->add($render);
                                    });
                                }

                                if ($tr = $bc->getTermRender($id, $paged)) {
                                    $bc->add($tr);
                                }
                            } elseif (is_single()) {
                                $id = get_the_ID();

                                if ($acs = $bc->getPostAncestorsRender($id)) {
                                    array_walk($acs, function ($render) use ($bc) {
                                        $bc->add($render);
                                    });
                                }

                                if ($pr = $bc->getPostRender($id, $paged)) {
                                    $bc->add($pr);
                                }
                            }
                        }

                        if ($paged) {
                            $bc->add($bc->getRender(sprintf(__('Page %d', 'tify'), get_query_var('paged'))));
                        }
                        $e->stopPropagation();
                    }
                }
            }, 100);

            $this->built = true;
        }

        return $this;
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
            /**
             * Liste des noms de qualification des routes en correspondance avec la page d'accroche.
             * @var string[]
             */
            'routes'              => [],
            'show_option_none'    => __('Aucune page choisie', 'tify'),
            'title'               => $this->name,
            // Requête de la page d'affichage
            'wp_query'            => false,
        ];
    }

    /**
     * @inheritDoc
     */
    public function exists(): bool
    {
        return $this->post() instanceof QueryPostContract;
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
        return ($post = $this->post()) ? $post->getPath() : '';
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
        if ($this->exists()) {
            if ($post) {
                return $this->post()->getId() === intval($post->ID);
            } else {
                if (!is_null($this->globalCurrent)) {
                    return $this->globalCurrent;
                } elseif (in_array(Router::currentRouteName(), $this->get('routes', []))) {
                    return $this->globalCurrent = true;
                } else {
                    global $wp_query;

                    if ($wp_query->is_main_query()) {
                        if ($pagename = $wp_query->get('pagename', '')) {
                            $this->globalCurrent = ($this->getPath() === $pagename);
                        } elseif ($page_id = $wp_query->get('page_id', 0)) {
                            $this->globalCurrent = ($this->post()->getId() === intval($page_id));
                        } elseif ($this->objectType && $this->objectName) {
                            switch ($this->objectType) {
                                case 'post_type' :
                                    if ($this->objectName === 'post') {
                                        $this->globalCurrent = $wp_query->is_home();
                                    } else {
                                        $this->globalCurrent = $wp_query->is_post_type_archive($this->objectName);
                                    }
                                    break;
                                /*case 'taxonomy' :
                                    if ($this->objectName === 'category') {
                                        $this->globalCurrent = $wp_query->is_category();
                                    } elseif ($this->objectName === 'post_tag') {
                                        $this->globalCurrent = $wp_query->is_tag();
                                    } else {
                                        $this->globalCurrent = $wp_query->is_tax($this->objectName);
                                    }
                                    break;*/
                            }
                        }

                        if (is_null($this->globalCurrent)) {
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
                $this->set('id', $post_id = (int)get_option($this->getOptionName(), 0));
            }

            $this->post = ($post_id && ($post = get_post($post_id))) ? new QueryPost($post) : null;
        }

        return $this->post;
    }
}