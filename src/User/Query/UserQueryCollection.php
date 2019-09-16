<?php

namespace tiFy\User\Query;

use tiFy\Contracts\User\UserQueryCollection as UserQueryCollectionContract;
use tiFy\Contracts\User\UserQueryItem;
use tiFy\Kernel\Collection\QueryCollection;

/**
 * Class UserQueryCollection
 * @package tiFy\User\Query
 *
 * @deprecated Utiliser \tiFy\Wordpress\Query\QueryUsers en remplacement.
 */
class UserQueryCollection extends QueryCollection implements UserQueryCollectionContract
{
    /**
     * Liste des éléments déclarés.
     * @var UserQueryItem[] $items
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     */
    public function getIds()
    {
        return $this->collect()->pluck('ID')->all();
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayNames()
    {
        return $this->collect()->pluck('display_name')->all();
    }

    /**
     * {@inheritdoc}
     */
    public function getEmails()
    {
        return $this->collect()->pluck('user_email')->all();
    }

    /**
     * {@inheritdoc}
     */
    public function getLogins()
    {
        return $this->collect()->pluck('user_login')->all();
    }
}