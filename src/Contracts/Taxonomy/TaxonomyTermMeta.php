<?php

namespace tiFy\Contracts\Taxonomy;

interface TaxonomyTermMeta
{
    /**
     * Récupération d'une métadonné de taxonomie.
     *
     * @param int $term_id Identifiant de qualification du terme de taxonomie.
     * @param string $meta_key Clé d'identification de la métadonnée enregistrées en base de données.
     *
     * @return mixed[]
     */
    public function get($term_id, $meta_key);

    /**
     * Vérification si l'enregistrement de la métadonnée en base est de type unique.
     *
     * @param string $taxonomy Identifiant de qualification de la taxonomie associée.
     * @param string $meta_key Clé d'identification de la métadonnée enregistrées en base de données.
     *
     * @return bool
     */
    public function isSingle($taxonomy, $meta_key);

    /**
     * Déclaration d'une métadonné.
     *
     * @param string $taxonomy Identifiant de qualification de la taxonomie associée.
     * @param string $meta_key Clé d'identification de la métadonnée enregistrées en base de données.
     * @param bool $single Type d'enregistrement de la metadonnées en base. true (unique)|false (multiple).
     * @param callable $sanitize_callback Fonction ou Méthode de rappel appelé avant la sauvegarde en base de données.
     *
     * @return void
     */
    public function register($taxonomy, $meta_key, $single = false, $sanitize_callback = 'wp_unslash');

    /**
     * Enregistrement de metadonnées de taxonomie.
     *
     * @param int $term_id Identifiant de qualification du terme de taxonomie.
     * @param int $tt_id
     * @param string $taxonomy Identifiant de qualification de la taxonomie associée.
     *
     * @return void
     */
    public function save($term_id, $tt_id, $taxonomy);
}