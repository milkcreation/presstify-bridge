<?php declare(strict_types=1);

namespace tiFy\Contracts\Support;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;

interface ParamsBag extends ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /**
     * Création d'un instance basée sur une liste d'attributs.
     *
     * @param array Liste des attributs.
     *
     * @return static
     */
    public static function createFromAttrs($attrs): ParamsBag;

    /**
     * Récupération de la liste des attributs.
     *
     * @return array
     */
    public function all();

    /**
     * Suppression de la liste des attributs déclarés.
     *
     * @return static
     */
    public function clear();

    /**
     * Compte le nombre d'éléments.
     *
     * @return int
     */
    public function count();

    /**
     * Définition de la liste des attributs par défaut.
     *
     * @return array
     */
    public function defaults();

    /**
     * Suppression d'un ou plusieurs attributs.
     *
     * @param  array|string $keys Liste des indices des attributs à supprimer. Syntaxe à point permise.
     *
     * @return void
     */
    public function forget($keys): void;

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
     * Vérification d'existance d'un attribut de configuration.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     *
     * @return mixed
     */
    public function has($key);

    /**
     * Récupération de la liste des paramètres au format json.
     * @see http://php.net/manual/fr/function.json-encode.php
     *
     * @param int $options Options d'encodage.
     *
     * @return string
     */
    public function json($options = 0);

    /**
     * Récupération de la liste des clés d'indexes des attributs de configuration.
     *
     * @return string[]
     */
    public function keys();

    /**
     * Cartographie de donnée.
     *
     * @return static
     */
    public function map(&$value, $key);

    /**
     * Récupération d'un jeu d'attributs associé à une liste de clés d'indices.
     *
     * @param string[] $keys Liste des clés d'indice du jeu d'attributs à récupérer.
     *
     * @return array
     */
    public function only(array $keys): array;

    /**
     * Traitement de la liste des attributs.
     *
     * @return static
     */
    public function parse();

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
     * @return static
     */
    public function push($key, $value);

    /**
     * Définition d'un attribut.
     *
     * @param string|array $key Clé d'indexe de l'attribut, Syntaxe à point permise ou tableau associatif des attributs
     *                          à définir.
     * @param mixed $value Valeur de l'attribut si la clé d'index est de type string.
     *
     * @return static
     */
    public function set($key, $value = null);

    /**
     * Insertion d'un attribut au début d'une liste d'attributs.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed $value Valeur de l'attribut.
     *
     * @return static
     */
    public function unshift($value, $key);

    /**
     * Récupération de la liste des valeurs des attributs de configuration.
     *
     * @return mixed[]
     */
    public function values();
}