<?php

namespace tiFy\Taxonomy\Metabox\CustomHeader;

use tiFy\Metabox\MetaboxWpTermController;
use tiFy\Wordpress\Proxy\Field;
use WP_Term;

class CustomHeader extends MetaboxWpTermController
{
    /**
     * @inheritDoc
     */
    public function load($wp_screen)
    {
        add_action('admin_enqueue_scripts', function(){
            Field::get('media-image')->enqueue();
        });
    }

    /**
     * {@inheritDoc}
     *
     * @param WP_Term $term
     */
    public function content($term = null, $taxonomy = null, $args = null)
    {
        return (string) Field::get('media-image', array_merge([
            'media_library_title' => __('Personnalisation de l\'image d\'entête', 'tify'),
            'media_library_button' => __('Utiliser comme image d\'entête', 'tify'),
            'name' => '_custom_header',
            'value' => get_term_meta($term->term_id, '_custom_header', true)
        ], $this->all()));
    }

    /**
     * @inheritDoc
     */
    public function header($term = null, $taxonomy = null, $args = null)
    {
        return $this->item->getTitle() ? : __('Image d\'entête', 'tify');
    }

    /**
     * @inheritDoc
     */
    public function metadatas()
    {
        return [
            '_custom_header' => true
        ];
    }
}