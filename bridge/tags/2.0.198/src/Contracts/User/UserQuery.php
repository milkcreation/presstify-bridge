<?php

namespace tiFy\Contracts\User;

/**
 * Interface UserQuery
 * @package tiFy\Contracts\User
 *
 * @deprecated Utiliser \tiFy\Wordpress\Query\QueryUsers en remplacement.
 */
interface UserQuery
{
    /**
     * Récupération des données d'une liste d'élément selon des critères de requête
     *
     * @param \WP_User_Query|array $query_args Requête de récupération utilisateur|Liste des arguments de requête.
     *
     * @return array|UserQueryCollection|UserQueryItem[]
     */
    public function getCollection($query_args = null);

    /**
     * Récupération d'un élément
     *
     * @param string|int|\WP_User|null $id Login utilisateur Wordpress|Identifiant de qualification Wordpress
     * |Objet utilisateur Wordpress|Utilisateur Wordpress courant
     *
     * @return null|UserQueryItem
     */
    public function getItem($id = null);

    /**
     * Récupération d'un élément selon un attribut particulier
     *
     * @param string $key Identifiant de qualification de l'attribut. défaut name.
     * @param string $value Valeur de l'attribut
     *
     * @return null|UserQueryItem
     */
    public function getItemBy($key = 'login', $value);

    /**
     * Récupération du(es) role(s) utilisateur Wordpress du controleur.
     *
     * @return string|array
     */
    public function getObjectName();


    /**
     * Récupération d'une instance du controleur de liste d'éléments.
     *
     * @param UserQueryItem[] $items Liste des éléments.
     *
     * @return string
     */
    public function resolveCollection($items);

    /**
     * Récupération d'une instance du controleur de données d'un élément.
     *
     * @param \WP_User $wp_user Instance d'utilisateur Wordpress.
     *
     * @return string
     */
    public function resolveItem(\WP_User $wp_user);
}

