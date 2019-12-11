<?php

namespace tiFy\Wordpress\Partial\Partials\Accordion;

use tiFy\Partial\Partials\Accordion\AccordionItems;
use WP_Term;
use WP_Term_Query;

class AccordionWpTerms extends AccordionItems
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return $this->walk($this->items, 0, 0);
    }

    /**
     * {@inheritdoc}
     *
     * @param WP_Term_Query|array $args Requête de récupération de termes ou liste des arguments de requête de
     *                                  récupération.
     *                                  @see https://developer.wordpress.org/reference/classes/wp_term_query/
     */
    public function query($args)
    {
        if (!$args instanceof WP_Term_Query) :
            $args = new WP_Term_Query($args);
        endif;

        $items = $args->terms ? : [];

        array_walk($items, [$this, 'wrap']);
    }

    /**
     * {@inheritdoc}
     *
     * @param WP_Term $item Element
     * @param int $key Clé d'indice
     */
    public function wrap($item, $key = null)
    {
        $key = $item->term_id;

        if (!$item instanceof AccordionWpTerm) :
            $item = new AccordionWpTerm($key, $item);
        endif;

        return $this->items[$key] = $item;
    }
}