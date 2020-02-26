<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Encryption\Encrypter as EncrypterContract;

/**
 * @method static string encrypt(string $plain)
 * @method static mixed decrypt(string $hash)
 *
 * @see \tiFy\Encryption\Encrypter
 */
class Crypt extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return EncrypterContract
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
        return 'encrypter';
    }
}