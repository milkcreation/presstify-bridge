<?php

namespace tiFy\PostType\Metabox\Subtitle;

use tiFy\Metabox\MetaboxWpPostController;

class Subtitle extends MetaboxWpPostController
{
    /**
     * {@inheritdoc}
     */
    public function content($post = null, $args = null, $null = null)
    {
        return field('text', [
                'attrs' => [
                    'class' => 'widefat'
                ],
                'name'  => $this->get('name'),
                'value' => wp_unslash(get_post_meta($post->ID, $this->get('name'), true))
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function defaults()
    {
        return [
            'name'    => '_subtitle',
            'attrs'   => [
                'class'       => 'widefat',
                'placeholder' => __('Sous-titre', 'tify'),
                'style'       => 'margin-top:10px;margin-bottom:20px;background-color:#fff;font-size:1.4em;' .
                    ' height:1.7em;line-height:100%;margin:10 0 15px;outline:0 none;padding:3px 8px;' .
                    ' width:100%;'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function header($post = null, $args = null, $null = null)
    {
        return $this->item->getTitle() ? : __('Sous-titre', 'tify');
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