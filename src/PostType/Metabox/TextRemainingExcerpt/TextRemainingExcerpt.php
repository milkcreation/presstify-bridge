<?php

namespace tiFy\PostType\Metabox\TextRemainingExcerpt;

use tiFy\Metabox\MetaboxWpPostController;

class TextRemainingExcerpt extends MetaboxWpPostController
{
    /**
     * {@inheritdoc}
     */
    public function content($post = null, $args = null, $null = null)
    {
        return field(
            'text-remaining',
            [
                'name'  => 'excerpt',
                'value' => $post->post_excerpt,
                'max'   => $this->get('max')
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function defaults()
    {
        return [
            'max' => 255,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function header($post = null, $args = null, $null = null)
    {
        return $this->item->getTitle() ? : __('Extrait', 'tify');
    }

    /**
     * {@inheritdoc}
     */
    public function load($wp_screen)
    {
        add_action(
            'add_meta_boxes',
            function () {
                remove_meta_box('postexcerpt', $this->getPostType(), 'normal');
            }
        );

        add_action(
            'admin_enqueue_scripts',
            function () {
                field('text-remaining')->enqueue();
            }
        );
    }
}