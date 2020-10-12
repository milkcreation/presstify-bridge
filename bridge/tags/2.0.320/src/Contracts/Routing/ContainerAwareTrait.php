<?php declare(strict_types=1);

namespace tiFy\Contracts\Routing;

use Psr\Container\ContainerInterface as Container;

interface ContainerAwareTrait
{
    /**
     * Récupération de l'instance du conteneur d'injection de dépendances.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;

    /**
     * Définition du conteneur d'injection de dépendances.
     *
     * @param Container $container
     *
     * @return static
     */
    public function setContainer(Container $container): ContainerAwareTrait;
}