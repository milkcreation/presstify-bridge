<?php

namespace tiFy\User\Query;

use Illuminate\Support\Arr;
use tiFy\Contracts\User\UserQuery as UserQueryContract;
use WP_User;
use WP_User_Query;

/**
 * Class UserQuery
 * @package tiFy\User\Query
 *
 * @deprecated Utiliser \tiFy\Wordpress\Query\QueryUsers en remplacement.
 */
class UserQuery implements UserQueryContract
{
    /**
     * Role(s) utilisateur Wordpress.
     * @var string|array
     */
    protected $objectName = [];

    /**
     * Controleur de données d'un élément.
     * @var string
     */
    protected $itemController = UserQueryItem::class;

    /**
     * Controleur de données d'une liste d'éléments.
     * @var string
     */
    protected $collectionController = QueryUsers::class;

    /**
     * {@inheritdoc}
     */
    public function getCollection($query_args = null)
    {
        if ($query_args instanceof WP_User_Query) :
            $user_query = $query_args;
        elseif (is_array($query_args)) :
            if ($this->getObjectName() && !isset($query_args['role__in'])) :
                $query_args['role__in'] = Arr::wrap($this->getObjectName());
            endif;

            $user_query = new WP_User_Query($query_args);
        else :
            $user_query = new WP_User_Query(null);
        endif;

        $items = $user_query->get_total() ? array_map([$this, 'getItem'], $user_query->get_results()) : [];

        return $this->resolveCollection($items);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($id = null)
    {
        if (!$id) :
            $user = wp_get_current_user();
        elseif (is_numeric($id) && $id > 0) :
            $user = get_userdata($id);
        elseif (is_string($id)) :
            return $this->getItemBy(null, $id);
        else :
            $user = $id;
        endif;

        if (!$user instanceof WP_User) :
            return null;
        endif;

        if ($this->getObjectName() && !array_intersect($user->roles, Arr::wrap($this->getObjectName()))) :
            return null;
        endif;

        return $this->resolveItem($user);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemBy($key = 'login', $value)
    {
        $args = [
            'search' => $value,
            'number' => 1
        ];

        switch($key) :
            default :
            case 'user_login' :
            case 'login':
                $args['search_columns'] = ['user_login'];
                break;
            case 'user_email' :
            case 'email' :
                $args['search_columns'] = ['user_email'];
                break;
        endswitch;

        $user_query = new WP_User_Query($args);
        if ($users = $user_query->get_results()) :
            return $this->getItem(reset($users));
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
    public function resolveItem(\WP_User $wp_user)
    {
        $concrete = $this->itemController;

        return new $concrete($wp_user);
    }
}

