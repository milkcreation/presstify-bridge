<?php declare(strict_types=1);

namespace tiFy\Wordpress\Metabox;

use tiFy\Contracts\Metabox\{MetaboxDriver, MetaboxManager, MetaboxScreen as MetaboxScreenContract};
use tiFy\Wordpress\Metabox\Drivers\{Filefeed\Filefeed, Imagefeed\Imagefeed, Videofeed\Videofeed};
use tiFy\Wordpress\Routing\WpScreen;
use tiFy\Support\Proxy\{PostType, Request};
use WP_Post;
use WP_Screen;
use WP_Term;
use WP_User;

class Metabox
{
    /**
     * Instance du gestionnaire utilisateur.
     * @var MetaboxManager
     */
    protected $manager;

    /**
     * Liste des indices de qualification de données de post.
     * @var array
     */
    protected $postKeys = [
        'ID',
        'post_author',
        'post_date',
        'post_date_gmt',
        'post_content',
        'post_title',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status',
        'post_password',
        'post_name',
        'to_ping',
        'pinged',
        'post_modified',
        'post_modified_gmt',
        'post_content_filtered',
        'post_parent',
        'guid',
        'menu_order',
        'post_type',
        'post_mime_type',
        'comment_count',
    ];

    /**
     * Liste des indices de qualification de données de terme de taxonomie.
     * @var array
     */
    protected $termKeys = [
        'description',
        'name',
        'parent',
        'term_id',
        'slug',
    ];

    /**
     * Liste des indices de qualification de données utilisateur.
     * @var array
     */
    protected $userKeys = [
        'ID',
        'login',
        'first_name',
        'last_name',
    ];

    /**
     * CONSTRUCTEUR.
     *
     * @param MetaboxManager $manager Instance du gestionnaire de boîtes de saisie.
     *
     * @return void
     */
    public function __construct(MetaboxManager $manager)
    {
        $this->manager = $manager;

        $this->registerOverride();

        add_action('current_screen', function (WP_Screen $wp_screen) {
            $screen = new WpScreen($wp_screen);

            if ($screen->getObjectType() === 'post_type') {
                $post_type = $screen->getObjectName();
                $boxes     = [];

                if ($metaboxScreen = $this->manager->getScreen("{$post_type}@post_type")) {
                    $boxes = $metaboxScreen->getMetaboxes();
                }

                array_walk($boxes, function (MetaboxDriver $box) use ($post_type) {
                    if (($name = $box->name()) && ! in_array($name, $this->postKeys)) {
                        PostType::meta()->register($post_type, $name, true);
                    }
                });
            } elseif (($screen->getHookname() === 'options')) {
                $option_page = Request::input('option_page', '');
                $boxes       = [];

                if ($metaboxScreen = $this->manager->getScreen("{$option_page}@options")) {
                    $boxes = $metaboxScreen->getMetaboxes();
                }

                array_walk($boxes, function (MetaboxDriver $box) use ($option_page) {
                    if ($name = $box->name()) {
                        register_setting($option_page, $name);
                    }
                });
            } elseif ($screen->getObjectType() === 'taxonomy') {
                $taxonomy = $screen->getObjectName();
                $boxes    = [];

                if ($metaboxScreen = $this->manager->getScreen("{$taxonomy}@taxonomy")) {
                    $boxes = $metaboxScreen->getMetaboxes();
                }

                array_walk($boxes, function (MetaboxDriver $box) use ($taxonomy) {
                    if (($name = $box->name()) && ! in_array($name, $this->termKeys)) {
                        taxonomy()->term_meta()->register($taxonomy, $name, true);
                    }
                });
            } elseif ($screen->getObjectType() === 'user') {
                $roles = $screen->getObjectName();
                $boxes = [];

                /** @todo */
                if ($metaboxScreen = $this->manager->getScreen("{$roles}@user")) {
                    $boxes = $metaboxScreen->getMetaboxes();
                }

                array_walk($boxes, function (MetaboxDriver $box) use ($roles) {
                    /*if (($name = $box->name()) && ! in_array($name, $this->userKeys)) {
                        //user()->user_meta()->register($roles, $name, true);
                    }*/
                });
            }

            if ($screen->getObjectType()) {
                $boxes  = $this->manager->all();
                $tabRdr = function (...$args) {
                    echo $this->manager->render('tab', $args);
                };

                switch ($screen->getObjectType()) {
                    case 'post_type' :
                        /** 'edit_form_top',
                         * 'edit_form_before_permalink',
                         * 'edit_form_after_title',
                         * 'edit_form_after_editor',
                         * 'submitpage_box',
                         * 'submitpost_box',
                         * 'edit_page_form',
                         * 'edit_form_advanced',
                         * 'dbx_post_sidebar' */
                        add_action(
                            $screen->getObjectName() === 'page' ? 'edit_page_form' : 'edit_form_advanced',
                            $tabRdr
                        );

                        array_walk($boxes, function (MetaboxDriver $box) {
                            $box->setHandler(function (MetaboxDriver $box, WP_Post $wp_post) {
                                $box->set('wp_post', $wp_post);

                                if (is_null($box['value'])) {
                                    if ($name = $box->name()) {
                                        if (in_array($name, $this->postKeys)) {
                                            $box['value'] = $wp_post->{$name};
                                        } else {
                                            $box['value'] = get_post_meta($wp_post->ID, $box->name(), true);
                                        }
                                    }
                                }
                            });
                        });
                        break;
                    case 'options' :
                        add_settings_section('navtab', null, $tabRdr, $screen->getObjectName());

                        array_walk($boxes, function (MetaboxDriver $box) {
                            $box->setHandler(function (MetaboxDriver $box) {
                                if (is_null($box['value']) && ($name = $box->name())) {
                                    $box['value'] = get_option($name);
                                }
                            });
                        });
                        break;
                    case 'taxonomy' :
                        add_action($screen->getObjectName() . '_edit_form', $tabRdr, 10, 2);

                        array_walk($boxes, function (MetaboxDriver $box) {
                            $box->setHandler(function (MetaboxDriver $box, WP_Term $wp_term) {
                                $box->set('wp_term', $wp_term);

                                if ($name = $box->name()) {
                                    if (in_array($name, $this->termKeys)) {
                                        $box['value'] = $wp_term->{$name};
                                    } else {
                                        $box['value'] = get_term_meta($wp_term->term_id, $box->name(), true);
                                    }
                                }
                            });
                        });
                        break;
                    case 'user' :
                        add_action('show_user_profile', $tabRdr);
                        add_action('edit_user_profile', $tabRdr);

                        array_walk($boxes, function (MetaboxDriver $box) {
                            $box->setHandler(function (MetaboxDriver $box, WP_User $wp_user) {
                                $box->set('wp_user', $wp_user);

                                if ($name = $box->name()) {
                                    if (in_array($name, $this->userKeys)) {
                                        $box['value'] = $wp_user->{$name};
                                    } else {
                                        $box['value'] = get_user_meta($wp_user->ID, $box->name(), true);
                                    }
                                }
                            });
                        });
                        break;
                }
            }
        });

        /*
        add_action('add_meta_boxes', function () {
            foreach ($this->removes as $screen => $items) {
                if (preg_match('/(.*)@(post_type|taxonomy|user)/', $screen)) {
                    $screen = 'edit::' . $screen;
                }
                $WpScreen = WpScreen::get($screen);

                foreach ($items as $id => $contexts) {
                    foreach ($contexts as $context) {
                        remove_meta_box($id, $WpScreen->getObjectName(), $context);
                    }

                    // Hack Wordpress : Maintient du support de la modification du permalien.
                    if ($id === 'slugdiv' && ($WpScreen->getObjectType() === 'post_type')) {
                        $post_type = $WpScreen->getObjectName();

                        add_action('edit_form_before_permalink', function ($post) use ($post_type) {
                            if ($post->post_type !== $post_type) {
                                return;
                            }

                            $editable_slug = apply_filters('editable_slug', $post->post_name, $post);

                            echo field('hidden', [
                                'name'  => 'post_name',
                                'value' => esc_attr($editable_slug),
                                'attrs' => [
                                    'id'           => 'post_name',
                                    'autocomplete' => 'off',
                                ],
                            ]);
                        });
                    }
                }
            }
        }, 999999); */
    }

    /**
     * Déclaration des controleurs de surchage.
     *
     * @return void
     */
    public function registerOverride(): void
    {
        app()->add(MetaboxScreenContract::class, function () {
            return (new MetaboxScreen())->setManager($this->manager);
        });

        app()->add('metabox.driver.filefeed', function () {
            return new Filefeed();
        });

        app()->add('metabox.driver.imagefeed', function () {
            return new Imagefeed();
        });

        app()->add('metabox.driver.videofeed', function () {
            return new Videofeed();
        });
    }
}