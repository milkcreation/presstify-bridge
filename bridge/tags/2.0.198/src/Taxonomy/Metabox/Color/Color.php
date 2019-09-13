<?php

namespace tiFy\Taxonomy\Metabox\Color;

use tiFy\Metabox\MetaboxWpTermController;

class Color extends MetaboxWpTermController
{
    /**
     * {@inheritdoc}
     */
    public function content($term = null, $taxonomy = null, $args = null)
    {
        /** @var \WP_Term $term */
        return field(
            'colorpicker',
            [
                'name'    => '_color',
                'value'   => get_term_meta($term->term_id, '_color', true)
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function header($term = null, $taxonomy = null, $args = null)
    {
        return $this->item->getTitle() ?: __('Couleur', 'tify');
    }

    /**
     * {@inheritdoc}
     */
    public function load($wp_screen)
    {
        add_action(
            'admin_enqueue_scripts',
            function () {
                field('colorpicker')->enqueue();
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function metadatas()
    {
        return ['_color' => true];
    }
}