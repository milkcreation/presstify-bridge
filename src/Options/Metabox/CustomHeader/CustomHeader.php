<?php

namespace tiFy\Options\Metabox\CustomHeader;

use tiFy\Metabox\MetaboxWpOptionsController;

class CustomHeader extends MetaboxWpOptionsController
{
    /**
     * {@inheritdoc}
     */
    public function content($args = [], $null1 = null, $null2 = null)
    {
        return field(
            'media-image',
            array_merge(
                [
                    'media_library_title'  => __('Personnalisation de l\'image d\'entête', 'tify'),
                    'media_library_button' => __('Utiliser comme image d\'entête', 'tify'),
                    'name'                 => 'custom_header',
                    'value'                => get_option('custom_header')
                ],
                $this->all()
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function load($wp_screen)
    {
        add_action(
            'admin_enqueue_scripts',
            function(){
                field('media-image')->enqueue();
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function settings()
    {
        return ['custom_header'];
    }
}