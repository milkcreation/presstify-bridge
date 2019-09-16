<?php

namespace tiFy\PostType\Metabox\CustomHeader;

use tiFy\Metabox\MetaboxWpPostController;

class CustomHeader extends MetaboxWpPostController
{
    /**
     * {@inheritdoc}
     */
    public function load($wp_screen)
    {
        add_action('admin_enqueue_scripts', function(){
            field('media-image')->enqueue();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function content($post = null, $args = null, $null = null)
    {
        return field(
            'media-image',
            array_merge(
                [
                    'media_library_title' => __('Personnalisation de l\'image d\'entête', 'tify'),
                    'media_library_button' => __('Utiliser comme image d\'entête', 'tify'),
                    'name' => '_custom_header',
                    'value' => get_post_meta($post->ID, '_custom_header', true)
                ],
                $this->all()
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function header($post = null, $args = null, $null = null)
    {
        return $this->item->getTitle() ? : __('Image d\'entête', 'tify');
    }

    /**
     * {@inheritdoc}
     */
    public function metadatas()
    {
        return [
            '_custom_header' => true
        ];
    }
}