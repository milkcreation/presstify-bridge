<?php

namespace tiFy\Contracts\User;

use tiFy\Contracts\Kernel\ParamsBag;

/**
 * Interface UserQueryItem
 * @package tiFy\Contracts\User
 *
 * @deprecated Utiliser \tiFy\Wordpress\Query\QueryUser en remplacement.
 */
interface UserQueryItem extends ParamsBag
{
    /**
     * Vérification des habilitations.
     * @see WP_User::has_cap()
     * @see map_meta_cap()
     *
     * @param string $capability Nom de qalification de l'habiltation.
     * @param int $object_id  Optionel. Identifiant de qualification de l'object à vérifier lorsque $capability est de type "meta".
     *
     * @return bool
     */
    public function can($capability);

    /**
     * Récupération des renseignements biographiques.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Récupération du nom d'affichage publique.
     *
     * @return string
     */
    public function getDisplayName();

    /**
     * Récupération de l'email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Récupération du prénom.
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Récupération de l'identifiant de qualification Wordpress de l'utilisateur.
     *
     * @return int
     */
    public function getId();

    /**
     * Récupération du nom de famille.
     *
     * @return string
     */
    public function getLastName();

    /**
     * Récupération de l'identifiant de connection de l'utilisateur.
     *
     * @return string
     */
    public function getLogin();

    /**
     * Récupération du surnom.
     *
     * @return string
     */
    public function getNicename();

    /**
     * Récupération du pseudonyme.
     *
     * @return string
     */
    public function getNickname();

    /**
     * Récupération du mot de passe encrypté.
     *
     * @return string
     */
    public function getPass();

    /**
     * Récupération de la date de création du compte utilisateur.
     *
     * @return string
     */
    public function getRegistered();

    /**
     * Récupération de la liste des roles.
     *
     * @return array
     */
    public function getRoles();

    /**
     * Récupération de l'url du site internet associé à l'utilisateur.
     *
     * @return string
     */
    public function getUrl();

    /**
     * Récupération de l'objet utilisateur Wordpress associé.
     *
     * @return \WP_User
     */
    public function getUser();

    /**
     * Vérification de l'appartenance à un roles.
     *
     * @param string $role Identifiant de qualification du rôle.
     *
     * @return bool
     */
    public function hasRole($role);

    /**
     * Vérifie si l'utilisateur est connecté.
     *
     * @bool
     */
    public function isLoggedIn();
}