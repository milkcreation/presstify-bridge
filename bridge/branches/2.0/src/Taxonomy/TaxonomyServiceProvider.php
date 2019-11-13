<?php declare(strict_types=1);

namespace tiFy\Taxonomy;

use tiFy\Container\ServiceProvider;

class TaxonomyServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = [
        'taxonomy',
        'taxonomy.term-meta'
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->registerManager();
        $this->registerTermMeta();
    }

    /**
     * Déclaration du controleur principal.
     *
     * @return void
     */
    public function registerManager(): void
    {
        $this->getContainer()->share('taxonomy', function () {
            return new TaxonomyManager($this->getContainer());
        });
    }

    /**
     * Déclaration du controleur de gestion des metadonnées de terme d'une taxonomie.
     *
     * @return void
     */
    public function registerTermMeta(): void
    {
        $this->getContainer()->share('taxonomy.term-meta', function () {
            return new TaxonomyTermMeta();
        });
    }
}