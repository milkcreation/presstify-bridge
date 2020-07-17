<?php declare(strict_types=1);

namespace tiFy\Contracts\Container;

use League\Container\ServiceProvider\{ServiceProviderInterface, BootableServiceProviderInterface};

interface ServiceProvider extends ServiceProviderInterface, BootableServiceProviderInterface
{
    /**
     * Initialisation du fournisseur de service.
     *
     * @return void
     */
    public function boot();

    /**
     * Récupération de l'instance du controleur d'injection de dépendances.
     *
     * @return Container
     */
    public function getContainer();

    /**
     * Déclaration des services fournis.
     *
     * @return void
     */
    public function register();
}