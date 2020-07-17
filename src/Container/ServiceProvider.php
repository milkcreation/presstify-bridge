<?php declare(strict_types=1);

namespace tiFy\Container;

use League\Container\ServiceProvider\AbstractServiceProvider;
use tiFy\Contracts\Container\ServiceProvider as ServiceProviderContract;

class ServiceProvider extends AbstractServiceProvider implements ServiceProviderContract
{
    /**
     * Classe de rappel du conteneur de services.
     * @var Container
     */
    protected $container;

    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [];

    /**
     * @inheritDoc
     */
    public function boot() {}

    /**
     * @inheritDoc
     */
    public function register() {}
}