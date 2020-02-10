<?php declare(strict_types=1);

namespace tiFy\Contracts\Cache;

use Psr\Container\ContainerInterface as Container;

interface Cache
{
    /**
     * Délégation d'appel de l'instance de gestion de cache par défaut
     *
     * @param string $name
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call(string $name, array $parameters);

    /**
     * Récupération d'une instance de gestion de cache déclarée.
     *
     * @param string|null $name Nom de qualification de l'instance. Si null, retourne l'instance par défaut.
     *
     * @return Store
     */
    public function store(?string $name = null): Store;

    /**
     * Récupération du conteneur d'injection de dépendances.
     *
     * @return Container
     */
    public function getContainer(): ?Container;

    /**
     * Récupération du nom de qualification du gestionnaire de cache par défaut.
     *
     * @return string
     */
    public function getDefaultStore(): string;
}