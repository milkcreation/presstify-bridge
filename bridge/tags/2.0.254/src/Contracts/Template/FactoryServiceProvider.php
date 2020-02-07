<?php declare(strict_types=1);

namespace tiFy\Contracts\Template;

use tiFy\Contracts\Container\ServiceProvider;

interface FactoryServiceProvider extends ServiceProvider, FactoryAwareTrait
{
    /**
     * Récupération de l'alias de qualification complet d'un service fournis.
     *
     * @param string $alias Nom de qualification court.
     *
     * @return string
     */
    public function getFactoryAlias(string $alias): string;

    /**
     * Définition de la liste des service fournis pour le gabarit d'affichage.
     *
     * @return void
     */
    public function registerFactories(): void;
}