<?php

namespace tiFy\Contracts\Taxonomy;

/**
 * Interface TermQuery
 * @package tiFy\Contracts\Taxonomy
 *
 * @deprecated
 */
interface TermQuery
{
    /**
     * Récupération d'une liste d'élément selon des critères de requête
     *
     * @param \WP_Term_Query|array $query_args Liste des arguments de requête
     *
     * @return array|TermQueryCollection|TermQueryItem[]
     */
    public function getCollection($query_args = null);

    /**
     * Récupération d'un élément
     *
     * @param string|int|\WP_Term|null $id Nom de qualification (slug)|Identifiant de term Wordpress|Objet terme Wordpress|Terme de ma page courante
     *
     * @return null|object|TermQueryItem
     */
    public function getItem($id = null);

    /**
     * Récupération d'un élément selon un attribut particulier
     *
     * @param string $key Identifiant de qualification de l'attribut. défaut name.
     * @param string $value Valeur de l'attribut
     *
     * @return null|object|TermQueryItem
     */
    public function getItemBy($key = 'slug', $value);

    /**
     * Récupération de la taxonomie Wordpress du controleur
     *
     * @return string
     */
    public function getObjectName();

    /**
     * Récupération d'une instance du controleur de liste d'éléments.
     *
     * @param TermQueryItem[] $items Liste des éléments.
     *
     * @return string
     */
    public function resolveCollection($items);

    /**
     * Récupération d'une instance du controleur de données d'un élément.
     *
     * @param \WP_Term $wp_term Instance de terme de taxonomie Wordpress.
     *
     * @return string
     */
    public function resolveItem(\WP_Term $wp_term);
}