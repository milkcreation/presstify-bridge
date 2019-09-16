<?php

namespace tiFy\Taxonomy\Query;

use tiFy\Contracts\Taxonomy\TermQueryCollection as TermQueryCollectionContract;
use tiFy\Kernel\Collection\QueryCollection;
use WP_Term_Query;

/**
 * Class TermQueryCollection
 * @package tiFy\Taxonomy\Query
 *
 * @deprecated
 */
class TermQueryCollection extends QueryCollection implements TermQueryCollectionContract
{
    /**
     * Liste des éléments déclarés.
     * @var TermQueryItem[] $items
     */
    protected $items = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param array|WP_Term_Query $items
     *
     * @return void
     */
    public function __construct($items)
    {
        if ($items instanceof WP_Term_Query) :
            if ($items->terms) :
                array_walk($items->terms, [$this, 'wrap']);
            endif;
        else :
            $this->items = $items;
        endif;
    }

    /**
     * {@inheritdoc}
     */
    public function getIds()
    {
        return $this->collect()->pluck('term_id')->all();
    }

    /**
     * {@inheritdoc}
     */
    public function getNames()
    {
        return $this->collect()->pluck('name')->all();
    }

    /**
     * {@inheritdoc}
     */
    public function getSlugs()
    {
        return $this->collect()->pluck('slug')->all();
    }

    /**
     * {@inheritdoc}
     *
     * @param \WP_Term $term
     * @param int $key Clé d'indice de l'élément
     *
     * @return TermQueryItem
     */
    public function wrap($term, $key = null)
    {
        return $this->items[$key] = new TermQueryItem($term);
    }
}