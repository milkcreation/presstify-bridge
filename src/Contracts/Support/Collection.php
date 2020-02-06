<?php declare(strict_types=1);

namespace tiFy\Contracts\Support;

use ArrayAccess;
use Countable;
use Illuminate\Support\Collection as LaraCollection;
use IteratorAggregate;

interface Collection extends ArrayAccess, Countable, IteratorAggregate
{
    /**
     * Création d'un instance basée sur une liste d'éléments.
     *
     * @param array $items Liste des éléments.
     *
     * @return static
     */
    public static function createFromItems(array $items): Collection;

    /**
     * Récupération de la liste des éléments.
     *
     * @return array
     */
    public function all();

    /**
     * Récupération de la liste des éléments découpés en lot.
     *
     * @param int $size Nombre d'éléments par lot.
     *
     * @return LaraCollection[]
     */
    public function chunk(int $size): iterable;

    /**
     * Réinitialisation de la liste des éléments.
     *
     * @return static
     */
    public function clear(): Collection;

    /**
     * Instanciation du controleur de traitement d'une collection d'élément.
     *
     * @param null|array $items Liste des éléments à traiter. Si null utilise la liste des éléments déclarés.
     *
     * @return LaraCollection
     */
    public function collect($items = null);

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
     *
     * @return mixed
     */
    public function get($key);

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
     * Récupération d'un tableau indéxé ou dimensionné basé sur le couple key/value.
     *
     * @param string $value Clé d'indice de l'attribut utilisé comme valeur du tableau.
     * @param string $key Clé d'indice de l'attribut utilisé comme clé du tableau. Si null, clé d'indexe incrémentée.
     *
     * @return array
     */
    public function pluck($value, $key = null);

    /**
     * Définition d'un attribut.
     *
     * @param string|array $key Clé d'indice de l'élément ou tableau associatif des éléments à définir.
     * @param mixed $value Valeur de l'élément si la clé d'index est de type string.
     *
     * @return static
     */
    public function set($key, $value = null): Collection;

    /**
     * Traitement d'un élément.
     *
     * @param mixed $value Valeur de l'élément.
     * @param mixed $key Clé d'indice de l'élément.
     *
     * @return mixed
     */
    public function walk($value, $key = null);
}