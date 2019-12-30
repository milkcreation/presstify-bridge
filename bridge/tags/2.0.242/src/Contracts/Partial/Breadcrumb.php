<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

interface Breadcrumb extends PartialDriver
{
    /**
     * Ajout d'un élément de contenu au fil d'arianne.
     *
     * @param string|array|object|callable $part Element du fil d'ariane.
     *
     * @return static
     */
    public function addPart($part);

    /**
     * Désactivation de l'affichage.
     *
     * @return static
     */
    public function disable();

    /**
     * Activation de l'affichage.
     *
     * @return static
     */
    public function enable();


    /**
     * Récupération de la liste des éléments contenus dans le fil d'ariane.
     *
     * @return string[]
     */
    public function parsePartList();

    /**
     * Traitement d'un élément de contenu du fil d'arianne.
     *
     * @param string|array|object|callable $part Element du fil d'ariane.
     *
     * @return string
     */
    public function parsePart($part);

    /**
     * Ajout d'un élément de contenu en début de chaîne du fil d'arianne.
     *
     * @param string|array|object|callable $part Element du fil d'ariane.
     *
     * @return static
     */
    public function prependPart($part);

    /**
     * Supprime l'ensemble des éléments de contenu prédéfinis.
     *
     * @return static
     */
    public function reset();
}