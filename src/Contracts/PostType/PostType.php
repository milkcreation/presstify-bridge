<?php declare(strict_types=1);

namespace tiFy\Contracts\PostType;

use Psr\Container\ContainerInterface as Container;

interface PostType
{
    /**
     * Récupération d'une instance de type de post.
     *
     * @param string $name Nom de qualification du type de post.
     *
     * @return PostTypeFactory|null
     */
    public function get(string $name): ?PostTypeFactory;

    /**
     * Récupération de l'instance du conteneur d'injection de dépendances.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;

    /**
     * Récupération de l'instance du controleur de metadonnées de post.
     *
     * @return PostTypePostMeta|null
     */
    public function meta(): ?PostTypePostMeta;

    /**
     * Déclaration d'un type de post.
     *
     * @param string $name Nom de qualification du type de post.
     * @param array|PostTypeFactory $args Liste des arguments de configuration.
     *
     * @return PostTypeFactory|null
     */
    public function register(string $name, $args = []): ?PostTypeFactory;

    /**
     * Récupération de l'instance d'un statut de post.
     *
     * @param string $name Nom de qualification du statut. ex. publish|draft.
     * @param array $args Liste des arguments de configuration.
     *
     * @return PostTypeStatus
     */
    public function status(string $name, array $args = []): PostTypeStatus;
}