<?php declare(strict_types=1);

namespace tiFy\Contracts\Support;

use Psr\Container\ContainerInterface;

interface Callback
{
    /**
     * Appel d'une classe, d'une méthode de classe ou d'une fonction.
     *
     * @param mixed $callable Classe, méthode de classe ou fonction à appeler.
     * @param mixed ...$args Liste des variables passées en arguments.
     *
     * @return mixed
     */
    public static function make($callable, ...$args);

    /**
     * Récupération de l'instance du conteneur d'injection de dépendances.
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * Execution de la fonction de rappel.
     *
     * @param mixed ...$args Liste des variables passées en arguments.
     *
     * @return mixed
     */
    public function exec(...$args);

    /**
     * Vérification de permission.
     *
     * @return boolean
     */
    public function isPermit(): bool;

    /**
     * Définition de la fonction de rappel.
     *
     * @param mixed $callable Classe, méthode de classe ou fonction à appeler.
     *
     * @return static
     */
    public function set($callable): self;
}