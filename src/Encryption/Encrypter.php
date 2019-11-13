<?php declare(strict_types=1);

namespace tiFy\Encryption;

use tiFy\Contracts\Encryption\Encrypter as EncrypterContract;
use RuntimeException;

class Encrypter implements EncrypterContract
{
    /**
     * Clé secrète de hashage.
     * @var string
     */
    private $secret = '';

    /**
     * Clé privée de hashage.
     * @var string
     */
    private $private = '';

    /**
     * Algorithme d'encryption.
     * @var string AES-128-CBC|AES-256-CBC
     */
    private $cipher = 'AES-128-CBC';

    /**
     * CONSTRUCTEUR.
     *
     * @param string $secret Clé secrète de hashage.
     * @param string $private Clé privée de hashage.
     * @param string $cipher Algorithme d'encryption AES-128-CBC|AES-256-CBC.
     *
     * @return void
     */
    public function __construct($secret, $private, $cipher = 'AES-128-CBC')
    {
        switch($this->cipher = $cipher) {
            default:
                throw new RuntimeException(
                    __('Seuls les algorithmes d\'encryption AES-128-CBC et AES-256-CBC sont supportés.', 'tify')
                );
                break;
            case 'AES-128-CBC':
            case 'AES-256-CBC':
                $this->secret = hash('sha256', $secret);
                $this->private = substr(hash('sha256', $private), 0, 16);
                break;
        }
    }

    /**
     * @inheritDoc
     */
    public function decrypt(string $hash): ?string
    {
        return openssl_decrypt(base64_decode($hash), $this->cipher, $this->secret, 0, $this->private) ? : null;
    }

    /**
     * @inheritDoc
     */
    public function encrypt(string $plain): ?string
    {
        return base64_encode(openssl_encrypt($plain, $this->cipher, $this->secret, 0, $this->private)) ? : null;
    }
}