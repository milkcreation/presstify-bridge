<?php

namespace tiFy\PostType\Column\Excerpt;

use tiFy\Column\AbstractColumnDisplayPostTypeController;

class Excerpt extends AbstractColumnDisplayPostTypeController
{
    /**
     * {@inheritdoc}
     */
    public function header()
    {
        return $this->item->getTitle() ? : __('Extrait', 'tify');
    }

    /**
     * {@inheritdoc}
     */
    public function content($column_name = null, $post_id = null, $null = null)
    {
        if ($post = get_post($post_id)) :
            return $post->post_excerpt;
        endif;
    }
}