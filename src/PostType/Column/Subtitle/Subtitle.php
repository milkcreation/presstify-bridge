<?php

namespace tiFy\PostType\Column\Subtitle;

use tiFy\Column\AbstractColumnDisplayPostTypeController;

class Subtitle extends AbstractColumnDisplayPostTypeController
{
    /**
     * {@inheritdoc}
     */
    public function header()
    {
        return $this->item->getTitle() ? : __('Sous-titre', 'tify');
    }

    /**
     * {@inheritdoc}
     */
    public function content($column_name = null, $post_id = null, $null = null)
    {
        if ($subtitle = get_post_meta($post_id, '_subtitle', true)) :
            return $subtitle;
        else :
            return "<em style=\"color:#AAA;\">" . __('Aucun', 'tify') . "</em>";
        endif;
    }
}