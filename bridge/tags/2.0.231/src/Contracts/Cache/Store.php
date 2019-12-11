<?php declare(strict_types=1);

namespace tiFy\Contracts\Cache;

interface Store
{
    /**
     * Récupération de la date courante exprimée en secondes.
     *
     * @return int
     */
    public function currentTime(): int;

    /**
     * Récupération d'un élément en cache.
     *
     * @param string $key Clé de qualification de l'élément en cache.
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * Vérification d'existance d'un élément en cache.
     *
     * @param string $key Clé de qualification de l'élément en cache.
     *
     * @return boolean
     */
    public function has($key): bool;

    /**
     * Récupération du prefixe de qualification des éléments en cache.
     *
     * @return string
     */
    public function getPrefix(): string;

    /**
     * Décrémentation de la valeur d'un élément en cache.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return int|bool

    public function decrement(string $key, $value = 1); */

    /**
     * Suppression de tous les éléments en cache.
     *
     * @return boolean
     */
    public function flush(): bool;

    /**
     * Stockage d'un élément en cache pour une durée indéfinie.
     *
     * @param string $key Clé de qualification de l'élément en cache.
     * @param mixed $value Valeur de l'élément en cache.
     *
     * @return boolean
     */
    public function forever(string $key, $value): bool;

    /**
     * Suppression d'un élément en cache.
     *
     * @param string $key Clé de qualification de l'élément en cache.
     *
     * @return boolean
     */
    public function forget(string $key): bool;

    /**
     * Incrémentation de la valeur d'un élément en cache.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return int|bool

    public function increment(string $key, $value = 1);*/

    /**
     * Récupération d'une liste d'élément en cache.
     * {@internal Retourne null si aucun élément n'est retrouvé.}
     *
     * @param array $keys
     *
     * @return array
     */
    public function many(array $keys): ?array;

    /**
     * Stockage d'un élément en cache pour une durée exprimée en secondes.
     *
     * @param string $key Clé de qualification de l'élément en cache.
     * @param mixed $value Valeur de l'élément en cache.
     * @param int $seconds Nombre de secondes avant expiration du cache.
     *
     * @return boolean
     */
    public function put(string $key, $value, int $seconds): bool;

    /**
     * Stockage d'une liste d'élément en cache pour une durée exprimée en secondes.
     *
     * @param array $values Couple clé/valeur des données à mettre en cache.
     * @param int $seconds Nombre de secondes avant expiration du cache.
     *
     * @return boolean
     */
    public function putMany(array $values, int $seconds): bool;

    /**
     * Sérialisation de données.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function serialize($value): string;

    /**
     * Définition du prefixe de qualification des éléments en cache.
     *
     * @return static
     */
    public function setPrefix(string $prefix): Store;

    /**
     * Désérialisation de données.
     *
     * @param string $value
     *
     * @return mixed
     */
    public function unserialize(string $value);
}