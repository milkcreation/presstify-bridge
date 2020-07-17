<?php declare(strict_types=1);

namespace tiFy\Contracts\PostType;

interface PostTypePostMeta
{
    /**
     * Ajout d'une metadonnée.
     *
     * @param int $id Identifiant de qualification du post.
     * @param string $key Clé d'indice de la metadonnée.
     * @param mixed $value Valeur de la métadonnée à ajouter.
     *
     * @return int|null
     */
    public function add(int $id, string $key, $value): ?int;

    /**
     * Vérification d'existance d'une métadonnée déclarée.
     *
     * @param string $type Type de post.
     * @param string $key Clé d'indice de la metadonnée.
     *
     * @return bool
     */
    public function exists(string $type, string $key): bool;

    /**
     * Récupération d'une métadonnée.
     *
     * @param int $id Identifiant de qualification du post.
     * @param string $key Clé d'indice de la metadonnée.
     *
     * @return array|null
     */
    public function get(int $id, string $key): ?array;

    /**
     * Vérifie si une métadonnée déclarée est à occurence unique en base de données.
     *
     * @param string $type Type de post.
     * @param string $key Clé d'indice de la metadonnée.
     *
     * @return bool
     */
    public function isSingle(string $type, string $key): bool;

    /**
     * Récupération de la liste des clés d'indice des métadonnées déclarées.
     *
     * @param string|null $type Type de post.
     *
     * @return array
     */
    public function keys(?string $type = null): array;

    /**
     * Déclaration d'une métadonnée.
     *
     * @param string $type Type de post.
     * @param string $key Clé d'indice de la metadonnée.
     * @param bool $single Indicateur d'enregistrement de la métadonnée unique (true)|multiple (false).
     * @param string|callable $callback Méthode ou fonction de rappel avant l'enregistrement.
     *
     * @return static
     */
    public function register(
        string $type,
        string $key,
        bool $single = false,
        $callback = 'wp_unslash'
    ): PostTypePostMeta;

    /**
     * Déclaration d'une métadonnée à occurrence unique.
     *
     * @param string $type Type de post.
     * @param string $key Clé d'identification.
     * @param string|callable $callback Fonction de rappel.
     *
     * @return static
     */
    public function registerSingle(string $type, string $key, $callback = 'wp_unslash'): PostTypePostMeta;

    /**
     * Déclaration d'une métadonnée à occurrence multiple.
     *
     * @param string $type Type de post.
     * @param string $key Clé d'identification.
     * @param string|callable $callback Fonction de rappel.
     *
     * @return static
     */
    public function registerMulti(string $type, string $key, $callback = 'wp_unslash'): PostTypePostMeta;

    /**
     * Enregistrement de metadonnées.
     *
     * @param int $post_id Identifiant de qualification du post.
     * @param string $post_type Type de post.
     *
     * @return void
     */
    public function save(int $post_id, string $post_type): void;
}