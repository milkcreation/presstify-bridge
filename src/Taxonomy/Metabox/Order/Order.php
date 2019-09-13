<?php

namespace tiFy\Taxonomy\Metabox\Order;

use tiFy\Metabox\MetaboxWpTermController;

class Order extends MetaboxWpTermController
{
    /**
     * {@inheritdoc}
     */
    public function content($term = null, $taxonomy = null, $args = null)
    {
        return $this->viewer(
            'order',
            array_merge(
                $this->all(),
                [
                    'term'     => $term,
                    'taxonomy' => $taxonomy,
                ]
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function header($term = null, $taxonomy = null, $args = null)
    {
        return $this->item->getTitle() ? : __('Ordre d\'affichage', 'tify');
    }

    /**
     * {@inheritdoc}
     */
    public function metadatas()
    {
        return [
            '_order' => true
        ];
    }
}