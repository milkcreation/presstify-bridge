<?php

namespace tiFy\PostType\Query;

use tiFy\Contracts\PostType\PostQueryCollection as PostQueryCollectionContract;
use tiFy\Kernel\Collection\QueryCollection;
use WP_Query;
use WP_Post;

/**
 * Class PostQueryCollection
 * @package tiFy\PostType\Query
 *
 * @deprecated Utiliser \tiFy\Wordpress\Query\QueryPosts
 */
class PostQueryCollection extends QueryCollection implements PostQueryCollectionContract
{
    /**
     * Liste des éléments déclarés.
     * @var PostQueryItem[] $items
     */
    protected $items = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param array|WP_Query $items
     *
     * @return void
     */
    public function __construct($items)
    {
        if ($items instanceof WP_Query) :
            if ($items->posts) :
                array_walk($items->posts, [$this, 'wrap']);
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
        return $this->pluck('ID');
    }

    /**
     * {@inheritdoc}
     */
    public function getTitles()
    {
        return $this->pluck('post_title');
    }

    /**
     * {@inheritdoc}
     *
     * @param WP_Post $post
     * @param int $key Clé d'indice de l'élément.
     *
     * @return PostQueryItem
     */
    public function wrap($post, $key = null)
    {
        return $this->items[$key] = new PostQueryItem($post);
    }
}