<?php declare(strict_types=1);

namespace tiFy\Wordpress\PostType;

use tiFy\Contracts\PostType\{PostType as Manager, PostTypeFactory};
use tiFy\Wordpress\Contracts\PostType as PostTypeContract;

class PostType implements PostTypeContract
{
    /**
     * Instance du gestionnaire de types de post.
     * @var Manager
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param Manager $manager
     *
     * @return void
     */
    public function __construct(Manager $manager)
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

        events()->listen('post-type.factory.boot', function (PostTypeFactory $factory) {
            global $wp_post_types;

            if (!isset($wp_post_types[$factory->getName()])) {
                register_post_type($factory->getName(), $factory->all());
            }

            add_action('init', function () use ($factory) {
                if ($taxonomies = $factory->get('taxonomies', [])) {
                    foreach ($taxonomies as $taxonomy) {
                        register_taxonomy_for_object_type($taxonomy, $factory->getName());
                    }
                }
            }, 25);
        });

        add_action('save_post', [$this->manager->meta(), 'save'], 10, 2);
    }
}