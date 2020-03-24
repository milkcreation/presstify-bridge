<?php declare(strict_types=1);

namespace tiFy\Encryption;

use tiFy\Container\ServiceProvider;

class EncryptionServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'encrypter'
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('encrypter', function () {
            return new Encrypter(
                config('app.secret', md5(getenv('APP_URL'))),
                config('app.private', base64_encode(md5(getenv('APP_URL')))),
                config('app.cipher', 'AES-128-CBC')
            );
        });
    }
}