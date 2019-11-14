<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Filesystem\{Filesystem, LocalAdapter, LocalFilesystem};

/**
 * @method static Filesystem|null disk(string $name)
 * @method static LocalFilesystem local(string|LocalAdapter $root, array $config = [])
 * @method static LocalAdapter localAdapter(string $root, array $config = [])
 * @method static Filesystem|LocalFilesystem register(string $name, string|array|Filesystem $attrs)
 */
class Storage extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'storage';
    }
}