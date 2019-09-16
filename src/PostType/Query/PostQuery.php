<?php

namespace tiFy\PostType\Query;

use Illuminate\Support\Arr;
use tiFy\Contracts\PostType\PostQuery as PostQueryContract;
use WP_Query;
use WP_Post;

/**
 * Class PostQuery
 * @package tiFy\PostType\Query
 *
 * @deprecated Utiliser \tiFy\Wordpress\Query\QueryPosts
 */
class PostQuery implements PostQueryContract
{
    /**
     * Type de post Wordpress du controleur.
     * @var string|array
     */
    protected $objectName = 'any';

    /**
     * Controleur de données d'un élément.
     * @var string
     */
    protected $itemController = PostQueryItem::class;

    /**
     * Controleur de données d'une liste d'éléments.
     * @var string
     */
    protected $collectionController = PostQueryCollection::class;

    /**
     * {@inheritdoc}
     */
    public function getCollection($query_args = null)
    {
        if (is_null($query_args)) :
            global $wp_query;
        elseif($query_args instanceof WP_Query) :
            $wp_query = $query_args;
        elseif (is_array($query_args)) :
            $query_args['post_type'] = $query_args['post_type'] ?? $this->getObjectName();
            $query_args['posts_per_page'] = $query_args['posts_per_page']?? -1;

            $wp_query = new WP_Query($query_args);
        else :
            return [];
        endif;

        return $this->resolveCollection($wp_query);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($id = null)
    {
        if (!$id) :
            $post = get_the_ID();
        elseif (is_numeric($id) && $id > 0) :
            $post = $id;
        elseif (is_string($id)) :
            return $this->getItemBy(null, $id);
        else :
            $post = $id;
        endif;

        if (!$post = get_post($post)) :
            return null;
        endif;

        if (!$post instanceof WP_Post) :
            return null;
        endif;

        if (($post->post_type !== 'any') && !in_array($post->post_type, Arr::wrap($this->getObjectName()))) :
            return null;
        endif;

        return $this->resolveItem($post);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemBy($key = 'name', $value)
    {
        $args = [
            'post_type'      => 'any',
            'posts_per_page' => 1
        ];

        switch ($key) :
            default :
            case 'post_name' :
            case 'name' :
                $args['name'] = $value;
                break;
        endswitch;

        $wp_query = new WP_Query();
        $posts = $wp_query->query($args);

        if ($wp_query->found_posts) :
            return $this->getItem(reset($posts));
        endif;

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
    public function resolveItem(WP_Post $wp_post)
    {
        $concrete = $this->itemController;

        return new $concrete($wp_post);
    }
}

