<?php declare(strict_types=1);

namespace tiFy\Contracts\Taxonomy;

interface TaxonomyTermMeta
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
     * @param string $tax Taxonomie.
     * @param string $key Clé d'indice de la metadonnée.
     *
     * @return bool
     */
    public function exists(string $tax, string $key): bool;

    /**
     * Récupération d'une métadonnée.
     *
     * @param int $id Identifiant de qualification du terme de taxonomie.
     * @param string $key Clé d'indice de la metadonnée.
     *
     * @return array|null
     */
    public function get(int $id, string $key): ?array;

    /**
     * Vérifie si une métadonnée déclarée est à occurence unique en base de données.
     *
     * @param string $tax Taxonomie.
     * @param string $key Clé d'indice de la metadonnée.
     *
     * @return bool
     */
    public function isSingle(string $tax, string $key);

    /**
     * Récupération de la liste des clés d'indice des métadonnées déclarées.
     *
     * @param string|null $tax Taxonomie.
     *
     * @return array
     */
    public function keys(?string $tax = null): array;

    /**
     * Déclaration d'une métadonnée.
     *
     * @param string $tax Taxonomie.
     * @param string $key Clé d'indice de la metadonnée.
     * @param bool $single Indicateur d'enregistrement de la métadonnée unique (true)|multiple (false).
     * @param string|callable $callback Méthode ou fonction de rappel avant l'enregistrement.
     *
     * @return static
     */
    public function register(
        string $tax,
        string $key,
        bool $single = false,
        $callback = 'wp_unslash'
    ): TaxonomyTermMeta;

    /**
     * Déclaration d'une métadonnée à occurrence unique.
     *
     * @param string $tax Taxonomie.
     * @param string $key Clé d'identification.
     * @param string|callable $callback Fonction de rappel.
     *
     * @return static
     */
    public function registerSingle(string $tax, string $key, $callback = 'wp_unslash'): TaxonomyTermMeta;

    /**
     * Déclaration d'une métadonnée à occurrence multiple.
     *
     * @param string $tax Taxonomie.
     * @param string $key Clé d'identification.
     * @param string|callable $callback Fonction de rappel.
     *
     * @return static
     */
    public function registerMulti(string $tax, string $key, $callback = 'wp_unslash'): TaxonomyTermMeta;

    /**
     * Enregistrement de metadonnées.
     *
     * @param int $term_id Identifiant de qualification du terme de taxonomie.
     * @param string $taxonomy Taxonomie.
     *
     * @return void
     */
    public function save(int $term_id, string $taxonomy): void;
}