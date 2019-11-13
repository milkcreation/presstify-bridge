<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\PostType\{PostTypeFactory, PostTypeStatus};

/**
 * @method static PostTypeFactory|null get(string $name)
 * @method static PostTypeStatus status(string $name)
 */
class PostType extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'post-type';
    }
}