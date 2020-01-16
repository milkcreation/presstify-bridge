<?php declare(strict_types=1);

namespace tiFy\Wordpress\PageHook;

use Closure;
use tiFy\Contracts\Routing\Route;
use tiFy\Support\ParamsBag;
use tiFy\Wordpress\Contracts\{PageHookItem as PageHookItemContract, Query\QueryPost as QueryPostContract};
use tiFy\Wordpress\Query\QueryPost;
use WP_Post;
use WP_Query;
use WP_Term;

class PageHookItem extends ParamsBag implements PageHookItemContract
{
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
                        $wp_post_types[$post_type]->has_archive = true;
                        $wp_post_types[$post_type]->rewrite = false;

                        add_rewrite_rule(
                            $this->post()->getName() . '/([^/]+)/?$',
                            'index.php?post_type=' . $post_type . '&name=$matches[1]',
                            'top'
                        );

                        if ($this->post()->typeIn(['page'])) {
                            add_rewrite_rule(
                                $this->post()->getName() . '/' . $wp_rewrite->pagination_base . '/([0-9]{1,})/?$',
                                'index.php?page_id=' . $this->post()->getId() . '&paged=$matches[1]',
                                'top'
                            );
                        } else {
                            add_rewrite_rule(
                                $this->post()->getName() . '/' . $wp_rewrite->pagination_base . '/([0-9]{1,})/?$',
                                'index.php?p=' . $this->post()->getId() . '&post_type=' . $this->post()->getType() .
                                '&paged=$matches[1]',
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
                    }
                } elseif (preg_match('/(.*)@taxonomy/', $rewrite, $matches) && taxonomy_exists($matches[1])) {
                    global $wp_rewrite, $wp_taxonomies;

                    $taxonomy = $matches[1];

                    if (isset($wp_taxonomies[$taxonomy])) {
                        $wp_taxonomies[$taxonomy]->rewrite = false;

                        add_rewrite_rule(
                            $this->post()->getName() . '/([^/]+)/?$',
                            'index.php?taxonomy=' . $taxonomy . '&term=$matches[1]',
                            'top'
                        );

                        add_rewrite_rule(
                            $this->post()->getName() . '/([^/]+)/' . $wp_rewrite->pagination_base . '/([0-9]{1,})/?$',
                            'index.php?taxonomy=' . $taxonomy . '&term=$matches[1]&paged=$matches[2]',
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
            'rewrite'             => '',
            'route'               => false,
            'show_option_none'    => __('Aucune page choisie', 'tify'),
            'title'               => $this->name,
            'wp_query'            => true
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
                global $wp_query;

                if ($pagename = $wp_query->query['pagename'] ?? '') {
                    return $this->post()->getName() === $pagename;
                } elseif ($page_id = $wp_query->query['page_id'] ?? 0) {
                    return $this->post()->getName() === intval($page_id);
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
            $this->post = ($post_id && ($post = get_post($post_id)))
                ? new QueryPost($post) : null;
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