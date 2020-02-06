<?php

namespace tiFy\Taxonomy\Column\Color;

use tiFy\Column\AbstractColumnDisplayTaxonomyController;

class Color extends AbstractColumnDisplayTaxonomyController
{
    /**
     * {@inheritdoc}
     */
    public function header()
    {
        return $this->item->getTitle() ? : __('Couleur', 'tify');
    }

    /**
     * {@inheritdoc}
     */
    public function content($content = null, $column_name = null, $term_id = null)
    {
        if ($color = get_term_meta($term_id, '_color', true)) :
            echo "<div style=\"width:80px;height:80px;display:block;border:solid 1px #CCC;background-color:#F4F4F4;position:relative;\"><div style=\"position:absolute;top:5px;right:5px;bottom:5px;left:5px;background-color:{$color}\"></div></div>";
        endif;
    }
}