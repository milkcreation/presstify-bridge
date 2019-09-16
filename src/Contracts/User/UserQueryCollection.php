<?php

namespace tiFy\Contracts\User;

/**
 * Interface UserQueryCollection
 * @package tiFy\Contracts\User
 *
 * @deprecated Utiliser \tiFy\Wordpress\Query\QueryUsers en remplacement.
 */
interface UserQueryCollection
{
    /**
     * Récupération de la liste des identifiants de qualification.
     *
     * @return array
     */
    public function getIds();

    /**
     * Récupération de la liste des noms d'affichage.
     *
     * @return array
     */
    public function getDisplayNames();

    /**
     * Récupération de la liste des emails.
     *
     * @return array
     */
    public function getEmails();

    /**
     * Récupération de la liste des identifiants de connection.
     *
     * @return array
     */
    public function getLogins();
}