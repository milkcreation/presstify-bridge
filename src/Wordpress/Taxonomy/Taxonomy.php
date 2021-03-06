<?php declare(strict_types=1);

namespace tiFy\Wordpress\Taxonomy;

use tiFy\Contracts\Taxonomy\{Taxonomy as TaxonomyManager, TaxonomyFactory};
use tiFy\Wordpress\Contracts\Taxonomy as TaxonomyContract;
use WP_Taxonomy, WP_Term_Query;

class Taxonomy implements TaxonomyContract
{
    /**
     * Instance gestionnaire de taxonomies.
     * @var TaxonomyManager
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param TaxonomyManager $manager
     *
     * @return void
     */
    public function __construct(TaxonomyManager $manager)
    {
        $this->manager = $manager;

        add_action('init', function () {
            foreach (config('taxonomy', []) as $name => $attrs) {
                $this->manager->register($name, $attrs);
            }
        }, 0);

        add_action('init', function () {
            global $wp_taxonomies;

            foreach ($wp_taxonomies as $name => $taxonomy) {
                if (!$this->manager->get($name)) {
                    $this->manager->register($name, get_object_vars($taxonomy));
                }
            }
        }, 999999);

        add_action('edited_term', function (int $term_id, int $tt_id, string $taxonomy) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            } elseif (defined('DOING_AJAX') && DOING_AJAX) {
                return;
            }

            $this->manager->meta()->save($term_id, $taxonomy);
        }, 10, 3);

        events()->on('taxonomy.factory.boot', function (TaxonomyFactory $factory) {
            global $wp_taxonomies;

            if (!isset($wp_taxonomies[$factory->getName()])) {
                register_taxonomy($factory->getName(), $factory->get('object_type', []), $factory->all());
            }

            if ($wp_taxonomies[$factory->getName()] instanceof WP_Taxonomy) {
                $factory->setWpTaxonomy($wp_taxonomies[$factory->getName()]);
            }

            add_action('init', function () use ($factory) {
                if ($post_types = $factory->get('object_type', [])) {
                    $post_types = is_array($post_types) ? $post_types : array_map('trim', explode(',', $post_types));
                    foreach ($post_types as $post_type) {
                        register_taxonomy_for_object_type($factory->getName(), $post_type);
                    }
                }
            }, 25);

            add_action('admin_init', function () use ($factory) {
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                    return;
                } elseif (defined('DOING_AJAX') && DOING_AJAX) {
                    return;
                } elseif (!$initial_terms = $factory->get('initial_terms')) {
                    return;
                }

                foreach ($initial_terms as $slug => $name) {
                    if (!$term = get_term_by('slug', $slug, $factory->getName())) {
                        wp_insert_term($name, $factory->getName(), ['slug' => $slug]);
                    }
                }
            });
        });
    }

    /**
     * @inheritDoc
     */
    public function getTermsByOrder($taxonomy, $args = [], $order_meta_key = '_order')
    {
        unset($args['taxonomy']);

        $args = array_merge(['order' => 'ASC'], $args, [
            'taxonomy'   => $taxonomy,
            'meta_query' => [
                [
                    'relation' => 'OR',
                    [
                        'key'     => $order_meta_key,
                        'value'   => 0,
                        'compare' => '>=',
                        'type'    => 'NUMERIC',
                    ],
                    [
                        'key'     => $order_meta_key,
                        'compare' => 'NOT EXISTS',
                    ],
                ],
            ],
            'orderby'    => 'meta_value_num',
        ]);

        return (new WP_Term_Query())->query($args);
    }
}