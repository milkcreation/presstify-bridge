<?php declare(strict_types=1);

namespace tiFy\Contracts\Routing;

use Psr\Http\Message\UriInterface;
use League\Uri\UriInterface as LeagueUri;

interface UrlFactory
{
    /**
     * Résolution de sortie sous forme de chaîne de caractère.
     *
     * @return string
     */
    public function __toString();

    /**
     * Ajout d'une portion de chemin à la fin de l'url.
     *
     * @param string $segment Portion de chemin à ajouter.
     *
     * @return static
     */
    public function appendSegment(string $segment);

    /**
     * Suppression d'une portion de chemin de l'url.
     *
     * @param string $segment Portion de chemin à supprimer.
     *
     * @return static
     */
    public function deleteSegment(string $segment);

    /**
     * Récupération de la chaîne encodée de l'url.
     *
     * @return LeagueUri|UriInterface
     */
    public function get();

    /**
     * Retourne la chaîne décodée de l'url.
     *
     * @param boolean $raw Activation de la sortie brute.
     *
     * @return string
     */
    public function decoded(bool $raw = true);

    /**
     * Définition de l'url.
     *
     * @param string|UriInterface|LeagueUri $uri
     *
     * @return static
     */
    public function set($uri): UrlFactory;

    /**
     * Ajout d'arguments à l'url.
     *
     * @param array $args Liste des arguments de requête à inclure.
     *
     * @return static
     */
    public function with(array $args);

    /**
     * Suppression d'arguments de l'url.
     *
     * @param string[] $args Liste des arguments de requête à exclure.
     *
     * @return static
     */
    public function without(array $args);
}