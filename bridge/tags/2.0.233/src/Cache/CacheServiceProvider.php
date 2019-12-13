<?php declare(strict_types=1);

namespace tiFy\Cache;

use tiFy\Container\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'cache'
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('cache', function () {
            config([
                'cache.stores' => array_merge([
                    'database' => []
                ], config('cache.stores', []))
            ]);

            return new Cache($this->getContainer()->get('app'));
        });
    }
}