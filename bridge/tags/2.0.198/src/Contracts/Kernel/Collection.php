<?php

namespace tiFy\Contracts\Kernel;

use Iterator;
use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * Interface Collection
 * @package tiFy\Contracts\Kernel
 *
 * @deprecated
 */
interface Collection extends ArrayAccess, Countable, IteratorAggregate
{
    /**
     * Instanciation du controleur de traitement d'une collection d'élément.
     *
     * @param null|array $items Liste des éléments à traiter. Si null utilise la liste des éléments déclarés.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collect($items = null);

    /**
     * Récupération de la liste des éléments.
     *
     * @return array
     */
    public function all();

    /**
     * Compte le nombre d'éléments.
     *
     * @return int
     */
    public function count();

    /**
     * Récupération de l'élément d'itération courante.
     *
     * @return mixed
     */
    public function current();

    /**
     * Vérification d'existance d'éléments.
     *
     * @return boolean
     */
    public function exists();

    /**
     * Récupération d'un élément selon sa clé d'indice.
     *
     * @param mixed $key Clé d'indice.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Vérification d'existance d'un élément selon sa clé d'indice.
     *
     * @param mixed $key Clé d'indice.
     *
     * @return boolean
     */
    public function has($key);

    /**
     * Récupération de l'indice de l'élément d'itération courante.
     *
     * @return mixed
     */
    public function key();

    /**
     * Encapsulation d'un élément.
     *
     * @param mixed $item Définition de l'élément.
     * @param mixed $key Clé d'indice de l'élément.
     *
     * @return mixed
     */
    public function wrap($item, $key = null);

    /**
     * Récupération de l'instance de l'itération.
     *
     * @return Iterator
     */
    public function getIteration();

    /**
     * Récupération d'une instance de l'itérateur.
     *
     * @return Iterator
     */
    public function getIterator();

    /**
     * Vérification d'existance d'un élément depuis l'itération.
     *
     * @param mixed $key Clé d'indexe.
     *
     * @return boolean
     */
    public function offsetExists($key);

    /**
     * Récupération d'un élément depuis l'itération.
     *
     * @param mixed $key Clé d'indexe.
     *
     * @return boolean
     */
    public function offsetGet($key);

    /**
     * Définition d'un élément depuis l'itération.
     *
     * @param mixed $key Clé d'indexe.
     * @param mixed $value Valeur.
     *
     * @return boolean
     */
    public function offsetSet($key, $value);

    /**
     * Suppression d'un élément depuis l'itération.
     *
     * @param mixed $key Clé d'indexe.
     *
     * @return boolean
     */
    public function offsetUnset($key);

    /**
     * Récupération d'un tableau indéxé ou dimensionné basé sur le couple key/value.
     *
     * @param string $value Clé d'indice de l'attribut utilisé comme valeur du tableau.
     * @param string $key Clé d'indice de l'attribut utilisé comme clé du tableau. Si null, clé d'indexe incrémentée.
     *
     * @return array
     */
    public function pluck($value, $key = null);

    /**
     * Récupération d'un élément depuis l'itération.
     *
     * @param mixed $key Clé d'indexe.
     *
     * @return mixed
     */
    public function __get($key);

    /**
     * Définition d'un élément depuis l'itération.
     *
     * @param mixed $key Clé d'indexe.
     * @param mixed $value Valeur.
     *
     * @return void
     */
    public function __set($key, $value);

    /**
     * Vérification d'existance d'un élément depuis l'itération.
     *
     * @param mixed $key Clé d'indexe.
     *
     * @return boolean
     */
    public function __isset($key);

    /**
     * Suppression d'un élément depuis l'itération.
     *
     * @param mixed $key Clé d'indexe.
     *
     * @return void
     */
    public function __unset($key);
}