<?php

namespace tiFy\Contracts\Routing;

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
    public function appendSegment($segment);

    /**
     * Suppression d'une portion de chemin de l'url.
     *
     * @param string $segment Portion de chemin à supprimer.
     *
     * @return static
     */
    public function deleteSegments($segment);

    /**
     * Récupération de la chaîne encodée de l'url.
     *
     * @return string
     */
    public function get();

    /**
     * Retourne la chaîne décodée de l'url.
     *
     * @return string
     */
    public function getDecode();

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