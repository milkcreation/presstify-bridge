<?php

namespace tiFy\PostType\Column\PostThumbnail;

use tiFy\Column\AbstractColumnDisplayPostTypeController;
use tiFy\Kernel\Tools;

class PostThumbnail extends AbstractColumnDisplayPostTypeController
{
    /**
     * {@inheritdoc}
     */
    public function header()
    {
        return $this->item->getTitle() ? : '<span class="dashicons dashicons-format-image"></span>';
    }

    /**
     * Mise en file des scripts de l'interface d'administration.
     *
     * @return void
     */
    public function admin_enqueue_scripts()
    {
        partial('holder')->enqueue();

        $column_name = "column-{$this->item->getName()}";
        asset()->setInlineCss(
            ".wp-list-table th.{$column_name},.wp-list-table td.{$column_name}{width:80px;text-align:center;}" .
            ".wp-list-table td.{$column_name} img{max-width:80px;max-height:60px;}"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function content($column_name = null, $post_id = null, $null = null)
    {
        $attachment_id = get_post_thumbnail_id($post_id) ? : 0;

        // Vérifie l'existance de l'image
        if (($attachment = wp_get_attachment_image_src($attachment_id)) && isset($attachment[0]) && ($path = Tools::File()->getRelPath($attachment[0])) && file_exists(ABSPATH . $path)) :
            $thumb = wp_get_attachment_image($attachment_id, [60, 60], true);
        else :
            $thumb = partial(
                'holder',
                [
                    'width'            => 60,
                    'height'           => 60,
                ]
            );
        endif;

        return $thumb;
    }

    /**
     * {@inheritdoc}
     */
    public function load($wp_screen)
    {
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
    }
}