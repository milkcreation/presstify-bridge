<?php declare(strict_types=1);

namespace tiFy\Contracts\Support;

use Psr\Container\ContainerInterface;

interface Manager
{
    /**
     * Récupération de la liste des éléments définis.
     *
     * @return array
     */
    public function all(): iterable;

    /**
     * Initialisation du gestionnaire.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Récupération d'un élément défini.
     *
     * @param array $args Liste des arguments dynamique de récupération de l'élément.
     *
     * @return mixed
     */
    public function get(...$args);

    /**
     * Récupération de l'instance du conteneur d'injection de dépendances.
     *
     * @return ContainerInterface|null
     */
    public function getContainer(): ?ContainerInterface;

    /**
     * Déclaration d'un élément basée sur une liste d'attributs.
     *
     * @param string|int $key Indice de qualification de l'élément.
     * @param mixed $attrs Liste des attributs dynamiques.
     *
     * @return static
     */
    public function register($key, ...$args);

    /**
     * Définition d'un élément ou d'une liste d'éléments.
     *
     * @param string|int|array $key Indice de qualification de l'élément ou tableau associatif de la liste des éléments.
     * @param mixed $value Valeur de l'élément lorsque la clef est un indice.
     *
     * @return static
     */
    public function set($key, $value = null);

    /**
     * Traitement des éléments au moment de la définition.
     *
     * @param mixed $item Elément à définir
     * @param string|null Indice de qualification de l'élément.
     *
     * @return void
     */
    public function walk(&$item, $key = null): void;
}