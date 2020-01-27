<?php declare(strict_types=1);

namespace tiFy\Contracts\Routing;

use Psr\Container\ContainerInterface;

interface ContainerAwareTrait
{
    /**
     * Récupération du conteneur d'injection de dépendances.
     *
     * @return ContainerInterface
     */
    public function getContainer(): ?ContainerInterface;

    /**
     * Définition du conteneur d'injection de dépendances.
     *
     * @param ContainerInterface $container
     *
     * @return static
     */
    public function setContainer(ContainerInterface $container): ContainerAwareTrait;
}