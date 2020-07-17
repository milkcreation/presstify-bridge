<?php declare(strict_types=1);

namespace tiFy\Wordpress\PostType;

use tiFy\Contracts\PostType\{PostType as PostTypeManager, PostTypeFactory};
use tiFy\Wordpress\Contracts\PostType as PostTypeContract;
use WP_Post_Type;

class PostType implements PostTypeContract
{
    /**
     * Instance du gestionnaire de types de post.
     * @var PostTypeManager
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param PostTypeManager $manager
     *
     * @return void
     */
    public function __construct(PostTypeManager $manager)
    {
        $this->manager = $manager;

        add_action('init', function () {
            foreach (config('post-type', []) as $name => $attrs) {
                $this->manager->register($name, $attrs);
            }
        }, 1);

        add_action('init', function () {
            global $wp_post_types;

            foreach ($wp_post_types as $name => $attrs) {
                if (!$this->manager->get($name)) {
                    $this->manager->register($name, get_object_vars($attrs));
                }
            }
        }, 999999);

        add_action('save_post', function (int $post_id) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            } elseif (defined('DOING_AJAX') && DOING_AJAX) {
                return;
            } elseif (!$post = get_post($post_id)) {
                return;
            } elseif (('page' === $post->post_type) && !current_user_can('edit_page', $post_id)) {
                return;
            } elseif (('page' !== $post->post_type) && !current_user_can('edit_post', $post_id)) {
                return;
            }

            $this->manager->meta()->save($post_id, $post->post_type);
        });

        events()->listen('post-type.factory.boot', function (PostTypeFactory $factory) {
            global $wp_post_types;

            if (!isset($wp_post_types[$factory->getName()])) {
                register_post_type($factory->getName(), $factory->all());
            }

            if ($wp_post_types[$factory->getName()] instanceof WP_Post_Type) {
                $factory->setWpPostType($wp_post_types[$factory->getName()]);
            }

            add_action('init', function () use ($factory) {
                if ($taxonomies = $factory->get('taxonomies', [])) {
                    foreach ($taxonomies as $taxonomy) {
                        register_taxonomy_for_object_type($taxonomy, $factory->getName());
                    }
                }
            }, 25);
        });
    }
}