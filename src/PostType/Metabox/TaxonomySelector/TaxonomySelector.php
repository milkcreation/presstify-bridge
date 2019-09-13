<?php

namespace tiFy\PostType\Metabox\TaxonomySelector;

use tiFy\Metabox\MetaboxWpPostController;

class TaxonomySelector extends MetaboxWpPostController
{
    /**
     * {@inheritdoc}
     */
    public function content($post = null, $args = null, $null = null)
    {
        $terms = \get_terms([
            'taxonomy'   => $this->get('taxonomy'),
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key'     => '_order',
                    'value'   => 0,
                    'compare' => '>=',
                    'type'    => 'NUMERIC',
                ],
                [
                    'key'     => '_order',
                    'compare' => 'NOT EXISTS',
                ],
            ],
            'orderby'    => 'meta_value_num',
            'order'      => 'ASC',
            'get'        => 'all',
        ]);

        if (is_wp_error($terms)) :
            return;
        endif;

        $this->set('taxonomy', (array)$this->get('taxonomy'));
        $checked = wp_get_object_terms($post->ID, $this->get('taxonomy'), array_merge($args, ['fields' => 'ids']));

        $items = [];
        if ($this->get('multiple', true)) :
            /** @var \WP_Term $t */
            foreach ($terms as $t) :
                $items[] = [
                    'label'    => [
                        'content' => $t->name,
                    ],
                    'checkbox' => [
                        'name'    => "tax_input[{$t->taxonomy}][]",
                        'value'   => is_taxonomy_hierarchical($t->taxonomy) ? $t->term_id : $t->name,
                        'checked' => in_array($t->term_id, $checked),
                    ],
                ];
            endforeach;
        else :
            /** @var \WP_Term $t */
            foreach ($terms as $t) :
                $items[] = [
                    'label' => [
                        'content' => $t->name,
                    ],
                    'radio' => [
                        'name'    => "tax_input[{$t->taxonomy}][]",
                        'value'   => is_taxonomy_hierarchical($t->taxonomy) ? $t->term_id : $t->name,
                        'checked' => in_array($t->term_id, $checked),
                    ],
                ];
            endforeach;
        endif;

        $this->set('items', $items);
        $this->set('value', get_post_meta($post->ID, $this->get('name'), true));

        return $this->viewer('content', $this->all());
    }

    /**
     * {@inheritdoc}
     */
    public function defaults()
    {
        return [
            'multiple' => true,
            'name'     => '_related_tax',
            'taxonomy' => 'category'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function header($post = null, $args = null, $null = null)
    {
        return $this->item->getTitle() ?: __('Catégories associées', 'tify');
    }

    /**
     * {@inheritdoc}
     */
    public function load($current_screen)
    {
        add_action(
            'admin_enqueue_scripts',
            function () {
                \wp_enqueue_style(
                    'MetaboxesPostTypeTaxonomySelector',
                    asset()->url('post-type/metabox/taxonomy-selector/css/styles.css')
                );
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function metadatas()
    {
        return [
            $this->get('name') => true
        ];
    }
}