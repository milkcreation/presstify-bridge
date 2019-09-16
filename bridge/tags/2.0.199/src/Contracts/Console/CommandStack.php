<?php declare(strict_types=1);

namespace tiFy\Contracts\Console;

use Symfony\Component\Lock\LockInterface as Lock;

/**
 * USAGE :
 * Liste des commandes disponibles
 * -------------------------------
 * php console list
 *
 * TIPS :
 * Arrêt complet des commandes CLI lancées
 * ---------------------------------------
 * pkill -9 php
 */
interface CommandStack extends Command
{
    /**
     * Ajout d'un nom de qualification à la liste des commandes à exécuter.
     *
     * @param string $name Nom de qualification de la commande.
     *
     * @return static
     */
    public function addStack(string $name): CommandStack;

    /**
     * Récupération de l'instance du verrou.
     *
     * @return Lock|null
     */
    public function getLock(): ?Lock;

    /**
     * Récupération de la liste des noms de qualification des commandes à excécuter.
     *
     * @return string[]
     */
    public function getStack(): array;

    /**
     * Définition de la liste des arguments passés à une commande lors de son exécution.
     *
     * @param string $name Nom de qualification de la commande.
     * @param array $args Liste des arguments à exécuter.
     *
     * @return $this
     */
    public function setCommandArgs($name, array $args): CommandStack;

    /**
     * Définition de la liste des arguments par défaut passés à toutes les commandes lors de leur exécution.
     *
     * @param array $args Liste des arguments par défaut.
     *
     * @return $this
     */
    public function setDefaultArgs(array $args): CommandStack;

    /**
     * Définition du verrou.
     *
     * @param Lock $lock
     *
     * @return $this
     */
    public function setLock(Lock $lock): CommandStack;

    /**
     * Définition d'une liste de noms de qualification de commandes à exécuter.
     *
     * @param string[] $stack
     *
     * @return $this
     */
    public function setStack(array $stack): CommandStack;
}