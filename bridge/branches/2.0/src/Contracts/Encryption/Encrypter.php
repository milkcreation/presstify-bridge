<?php declare(strict_types=1);

namespace tiFy\Contracts\Encryption;

interface Encrypter
{
    /**
     * Décryptage.
     *
     * @param string $hash Texte à décrypter.
     * @param string $secret Clé secrète de hashage.
     * @param string $private Clé privée de hashage.
     *
     * @return string
     */
    public function decrypt(string $hash, ?string $secret = null, ?string $private = null): string;

    /**
     * Encryptage.
     *
     * @param string $plain Texte à encrypter.
     * @param string $secret Clé secrète de hashage.
     * @param string $private Clé privée de hashage.
     *
     * @return string
     */
    public function encrypt(string $plain, ?string $secret = null, ?string $private = null): string;

    /**
     * Generation.
     *
     * @param int $length Longueur de la chaine.
     * @param boolean $special_chars Activation des caractère spéciaux. !@#$%^&*().
     * @param boolean $extra_special_chars Activation des caractère spéciaux complémentaires. -_ []{}<>~`+=,.;:/?|.
     *
     * @return string
     */
    public function generate(int $length = 12, bool $special_chars = true, bool $extra_special_chars = false): string;

    /**
     * Récupération de la clée secrète de hashage.
     *
     * @return string
     */
    public function getSecret(): string;

    /**
     * Récupération de la clé privée de hashage.
     *
     * @return string
     */
    public function getPrivate(): string;
}