<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Metabox\{
    MetaboxContext as Context,
    MetaboxDriver as Driver,
    MetaboxManager as Manager,
    MetaboxScreen as Screen
};

/**
 * @method static Driver add(string $name, string|array|Driver $metabox)
 * @method static Screen addScreen(string $name, string|array|Screen $item)
 * @method static Manager registerContext(string $name, Context $context)
 * @method static Manager registerDriver(string $name, Driver $driver)
 * @method static string render(string $context)
 * @method static Manager stack(string|Screen $screen, string|Context $context, Driver[]|array[]|string[] $metaboxes = [])
 */
class Metabox extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'metabox';
    }
}