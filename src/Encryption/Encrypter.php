<?php declare(strict_types=1);

namespace tiFy\Encryption;

use tiFy\Contracts\Encryption\Encrypter as EncrypterContract;

class Encrypter implements EncrypterContract
{
    /**
     * Clé secrète de hashage.
     * @var string
     */
    private $secret = null;

    /**
     * Clé privée de hashage.
     * @var string
     */
    private $private = null;

    /**
     * CONSTRUCTEUR.
     *
     * @param string $secret Clé secrète de hashage.
     * @param string $private Clé privée de hashage.
     *
     * @return void
     */
    public function __construct($secret = null, $private = null)
    {
        $this->secret = $secret ? : NONCE_KEY;
        $this->private = $private ? : NONCE_SALT;
    }

    /**
     * Traitement de l'action de cryptage ou de decryptage.
     *
     * @param string $value Valeur à traiter.
     * @param string $action Traitement de la valeur à réaliser. encrypt|decrypt. encrypt par défaut.
     * @param string $secret Clé secrète de hashage.
     * @param string $private Clé privée de hashage.
     *
     * @return bool|string
     */
    protected function handle($value, $action = 'encrypt', $secret = null, $private = null)
    {
        $instance = new static($secret, $private);
        $secret_key = $instance->getSecret();
        $secret_iv = $instance->getPrivate();

        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        switch($action) {
            default :
            case 'encrypt' :
                return base64_encode(openssl_encrypt($value, $encrypt_method, $key, 0, $iv));
                break;
            case 'decrypt' :
                return openssl_decrypt(base64_decode($value), $encrypt_method, $key, 0, $iv);
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public function decrypt(string $hash, ?string $secret = null, ?string $private = null): string
    {
        return $this->handle($hash, 'decrypt', $secret, $private);
    }

    /**
     * @inheritdoc
     */
    public function encrypt($plain, ?string $secret = null, ?string $private = null): string
    {
        return $this->handle($plain, 'encrypt', $secret, $private);
    }

    /**
     * @inheritdoc
     *
     * @todo Supprimer l'utilisation du genérateur Wordpress
     */
    public function generate(int $length = 12, bool $special_chars = true, bool $extra_special_chars = false): string
    {
        return (string)wp_generate_password($length, $special_chars, $extra_special_chars);
    }

    /**
     * @inheritdoc
     */
    final public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @inheritdoc
     */
    final public function getPrivate(): string
    {
        return $this->private;
    }
}