<?php declare(strict_types=1);

namespace tiFy\Contracts\User;

use Psr\Container\ContainerInterface;
use tiFy\User\UserMeta;
use tiFy\User\Metadata\Option;

interface User
{
    /**
     * Récupération de l'instance de l'injecteur de dépendances.
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * Récupération de l'instance de traitement des métadonnées utilisateur.
     *
     * @return UserMeta
     */
    public function meta(): UserMeta;

    /**
     * Récupération de l'instance de traitement des options utilisateur.
     *
     * @return Option
     */
    public function option(): Option;

    /**
     * Récupération de l'instance de traitement des roles utilisateur.
     *
     * @return RoleManager
     */
    public function role(): RoleManager;

    /**
     * Résolution d'un service fourni.
     *
     * @param string $alias Nom de qualification du service.
     *
     * @return mixed|object
     */
    public function resolve($alias);
}
