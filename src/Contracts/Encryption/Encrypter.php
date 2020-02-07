<?php declare(strict_types=1);

namespace tiFy\Contracts\Encryption;

interface Encrypter
{
    /**
     * Décryptage.
     *
     * @param string $hash Chaine de caractère à décrypter.
     *
     * @return string|null
     */
    public function decrypt(string $hash): ?string;

    /**
     * Encryptage.
     *
     * @param string $plain Chaine de caractère à encrypter.
     *
     * @return string|null
     */
    public function encrypt(string $plain): ?string;
}