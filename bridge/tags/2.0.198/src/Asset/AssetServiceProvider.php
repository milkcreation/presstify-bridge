<?php declare(strict_types=1);

namespace tiFy\Asset;

use tiFy\Container\ServiceProvider;

class AssetServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = ['asset'];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('asset', function () {
            return new Asset($this->getContainer()->get('app'));
        });
    }
}