<?php declare(strict_types=1);

namespace tiFy\Wordpress\Proxy;

use tiFy\Support\Proxy\AbstractProxy;
use tiFy\Wordpress\Contracts\{PageHook as PageHookContract, PageHookItem};

/**
 * @method static array all()
 * @method static PageHookItem|null current()
 * @method static string|null currentName()
 * @method static PageHookItem|null get(string $name)
 * @method static PageHookContract set(array|string $name, mixed $value = null)
 */
class PageHook extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return PageHookContract
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
        return 'wp.page-hook';
    }
}