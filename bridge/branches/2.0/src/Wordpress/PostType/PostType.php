<?php

namespace tiFy\Wordpress\PostType;

use tiFy\Contracts\PostType\PostTypeFactory;
use tiFy\Contracts\PostType\PostType as PostTypeManager;
use tiFy\Wordpress\Contracts\PostType as PostTypeContract;

class PostType implements PostTypeContract
{
    /**
     * Instance du controleur de gestion des types de contenu.
     * @var PostTypeManager
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param PostTypeManager $manager Instance du controleur de gestion des types de contenu.
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

        events()->listen('post-type.factory.boot', function (PostTypeFactory $factory) {
            global $wp_post_types;

            if(!isset($wp_post_types[$factory->getName()])) {
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