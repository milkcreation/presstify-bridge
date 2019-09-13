<?php

namespace tiFy\Contracts\Kernel;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;

/**
 * Interface ParamsBag
 * @package tiFy\Contracts\Kernel
 *
 * @deprecated Utiliser tiFy\Contracts\Support\ParamsBag en remplacement
 */
interface ParamsBag extends ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /**
     * Récupération de la liste des attributs.
     *
     * @return array
     */
    public function all();

    /**
     * Définition de la liste des attributs par défaut.
     *
     * @return array
     */
    public function defaults();

    /**
     * Récupération d'un attribut.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed $default Valeur de retour par defaut lorsque l'attribut n'est pas défini.
     *
     * @return mixed
     */
    public function get($key, $default = '');

    /**
     * Compte le nombre d'éléments.
     *
     * @return int
     */
    public function count();

    /**
     * Vérification d'existance d'un attribut de configuration.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     *
     * @return mixed
     */
    public function has($key);

    /**
     * Récupération de la liste des clés d'indexes des attributs de configuration.
     *
     * @return string[]
     */
    public function keys();

    /**
     * Traitement de la liste des attributs.
     *
     * @param array $attrs Liste des attribut à traiter.
     *
     * @return void
     */
    public function parse($attrs = []);

    /**
     * Récupére la valeur d'un attribut avant de le supprimer.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed $default Valeur de retour par defaut lorsque l'attribut n'est pas défini.
     *
     * @return mixed
     */
    public function pull($key, $default = null);

    /**
     * Insertion d'un attribut à la fin d'une liste d'attributs.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed $value Valeur de l'attribut.
     *
     * @return mixed
     */
    public function push($key, $value);

    /**
     * Définition d'un attribut.
     *
     * @param string|array $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed $value Valeur de l'attribut.
     *
     * @return $this
     */
    public function set($key, $value = null);

    /**
     * Insertion d'un attribut au début d'une liste d'attributs.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed $value Valeur de l'attribut.
     *
     * @return mixed
     */
    public function unshift($value, $key);

    /**
     * Récupération de la liste des valeurs des attributs de configuration.
     *
     * @return mixed[]
     */
    public function values();

    /**
     * Récupération d'une intance de l'itérateur.
     *
     * @return callable
     */
    public function getIterator();

    /**
     * Vérification d'existance d'un élément d'itération.
     *
     * @param string|int $key Clé d'indexe.
     *
     * @return boolean
     */
    public function offsetExists($key);

    /**
     * Récupération d'un élément d'itération.
     *
     * @param string|int $key Clé d'indexe.
     *
     * @return boolean
     */
    public function offsetGet($key);

    /**
     * Définition d'un élément d'itération.
     *
     * @param string|int $key Clé d'indexe.
     * @param mixed $value Valeur.
     *
     * @return boolean
     */
    public function offsetSet($key, $value);

    /**
     * Suppression d'un élément d'itération.
     *
     * @param string|int $key Clé d'indexe.
     *
     * @return boolean
     */
    public function offsetUnset($key);

    /**
     * Récupération d'un élément d'itération.
     *
     * @param string|int $key Clé d'indexe.
     *
     * @return mixed
     */
    public function __get($key);

    /**
     * Définition d'un élément d'itération.
     *
     * @param string|int $key Clé d'indexe.
     * @param mixed $value Valeur.
     *
     * @return void
     */
    public function __set($key, $value);

    /**
     * Vérification d'existance d'un élément d'itération.
     *
     * @param string|int $key Clé d'indexe.
     *
     * @return boolean
     */
    public function __isset($key);

    /**
     * Suppression d'un élément d'itération.
     *
     * @param string|int $key Clé d'indexe.
     *
     * @return void
     */
    public function __unset($key);
}