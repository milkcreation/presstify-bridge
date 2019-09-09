<?php

namespace tiFy\Taxonomy\Query;

use Illuminate\Support\Arr;
use tiFy\Contracts\Taxonomy\TermQuery as TermQueryContract;
use WP_Term;
use WP_Term_Query;

/**
 * Class TermQuery
 * @package tiFy\Taxonomy\Query
 *
 * @deprecated
 */
class TermQuery implements TermQueryContract
{
    /**
     * Taxonomie Wordpress du controleur
     * @var string
     */
    protected $objectName = '';

    /**
     * Controleur de données d'un élément
     * @var string
     */
    protected $itemController = TermQueryItem::class;

    /**
     * Controleur de données d'une liste d'éléments
     * @var string
     */
    protected $collectionController = TermQueryCollection::class;

    /**
     * {@inheritdoc}
     */
    public function getCollection($query_args = null)
    {
        if ($query_args instanceof WP_Term_Query) :
            $term_query = $query_args;
        elseif (is_array($query_args)) :
            $query_args['taxonomy'] = Arr::wrap($query_args['taxonomy'] ?? $this->getObjectName());
            $term_query = new WP_Term_Query($query_args);
        else :
            $term_query = new WP_Term_Query(null);
        endif;

        return $this->resolveCollection($term_query);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($id = null)
    {
        if (!$id) :
            $term = get_queried_object();
        elseif (is_numeric($id) && $id > 0) :
            if ((!$term = get_term($id)) || is_wp_error($term)) :
                return null;
            endif;
        elseif (is_string($id)) :
            return $this->getItemBy(null, $id);
        else :
            $term = $id;
        endif;

        if (!$term instanceof WP_Term) :
            return null;
        endif;

        if (!in_array($term->taxonomy, Arr::wrap($this->getObjectName()))) :
            return null;
        endif;

        return $this->resolveItem($term);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemBy($key = 'slug', $value)
    {
        switch ($key) :
            default :
                if (($term = get_term_by($key, $value, $this->getObjectName())) && !is_wp_error($term)) :
                    return $this->getItem($term);
                endif;
                break;
        endswitch;

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectName()
    {
        return $this->objectName;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveCollection($items)
    {
        $concrete = $this->collectionController;

        return new $concrete($items);
    }

    /**
     * {@inheritdoc}
     */
    public function resolveItem(WP_Term $wp_term)
    {
        $concrete = $this->itemController;

        return new $concrete($wp_term);
    }
}