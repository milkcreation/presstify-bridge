<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\User\{User as UserContract, UserMeta};

/**
 * @method static UserMeta meta()
 */
class User extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return UserContract
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @inheritDoc
     */
    public static function getInstanceIdentifier()
    {
        return 'user';
    }
}