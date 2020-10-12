<?php declare(strict_types=1);

namespace tiFy\Contracts\User;

interface UserMeta
{
    /**
     * Ajout d'une métadonnée.
     *
     * @param int $id
     * @param string $key
     * @param mixed $value
     *
     * @return int|null
     */
    public function add(int $id, string $key, $value): ?int;

    /**
     * Vérification d'existance d'une métadonnée déclarée.
     *
     * @param string $key Clé d'indice de la métadonnée.
     *
     * @return bool
     */
    public function exists(string $key): bool;

    /**
     * Récupération d'une métadonné.
     *
     * @param int $id
     * @param string $meta_key
     *
     * @return array
     */
    public function get(int $id, string $key): ?array;

    /**
     * Vérification si la métadonnée doit avoir une occurence unique par utilisateur en base de donnée.
     *
     * @param string $key
     *
     * @return bool
     */
    public function isSingle(string $key): bool;

    /**
     * Récupération de la listes des clés de métadonnées déclarées.
     *
     * @return string[]|array
     */
    public function keys(): array;

    /**
     * Déclaration d'une métadonnée.
     *
     * @param string $key Clé d'identification.
     * @param bool $single Activation de l'enregistrement unique.
     * @param string|callable $callback Fonction de rappel.
     *
     * @return static
     */
    public function register(string $key, bool $single = false, $callback = 'wp_unslash'): UserMeta;

    /**
     * Déclaration d'une métadonnée à occurrence unique.
     *
     * @param string $key Clé d'identification.
     * @param string|callable $callback Fonction de rappel.
     *
     * @return static
     */
    public function registerSingle(string $key, $callback = 'wp_unslash'): UserMeta;

    /**
     * Déclaration d'une métadonnée à occurrence multiple.
     *
     * @param string $key Clé d'identification.
     * @param string|callable $callback Fonction de rappel.
     *
     * @return static
     */
    public function registerMulti(string $key, $callback = 'wp_unslash'): UserMeta;

    /**
     * Enregistrement des metadonnées déclarées.
     *
     * @param int $user_id
     *
     * @return void
     */
    public function save(int $user_id): void;
}