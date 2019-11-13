<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

/**
 * @method static string encrypt(string $plain)
 * @method static mixed decrypt(string $hash)
 *
 * @see \tiFy\Encryption\Encrypter
 */
class Crypt extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'encrypter';
    }
}