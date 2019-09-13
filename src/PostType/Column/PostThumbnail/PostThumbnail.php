<?php

namespace tiFy\PostType\Column\PostThumbnail;

use tiFy\Column\AbstractColumnDisplayPostTypeController;
use tiFy\Wordpress\Query\QueryPost;
use tiFy\Wordpress\Proxy\Partial;

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
        Partial::get('holder')->enqueue();

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
        $qp = QueryPost::createFromId($post_id);

        if (!$thumb = $qp->getThumbnail([60, 60])) {
            $thumb = Partial::get('holder', [
                'width'  => 60,
                'height' => 60,
            ]);
        }

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