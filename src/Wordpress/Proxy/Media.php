<?php declare(strict_types=1);

namespace tiFy\Wordpress\Proxy;

use tiFy\Support\Proxy\AbstractProxy;

/**
 * @method static string|null getBase64Src(int $id)
 * @method static string|null getSrcFilename(string $src)
 */
class Media extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'wp.media';
    }
}