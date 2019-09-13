<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts;

use tiFy\Contracts\Support\Collection;
use WP_User_Query;

interface QueryUsers extends Collection
{
    /**
     * Création d'une instance basée sur une liste d'arguments.
     * @see https://codex.wordpress.org/Class_Reference/WP_User_Query
     *
     * @param array $args
     *
     * @return static
     */
    public static function createFromArgs(array $args): QueryUsers;

    /**
     * Création d'une instance basée sur une liste d'identifiant utilisateurs.
     *
     * @param int[] $ids Liste des identifiants utilisateurs.
     *
     * @return static
     */
    public static function createFromIds(array $ids): QueryUsers;

    /**
     * Récupération de la liste des identifiants de qualification.
     *
     * @return array
     */
    public function getIds(): array;

    /**
     * Récupération de la liste des noms d'affichage.
     *
     * @return array
     */
    public function getDisplayNames(): array;

    /**
     * Récupération de la liste des emails.
     *
     * @return array
     */
    public function getEmails(): array;

    /**
     * Récupération de la liste des identifiants de connection.
     *
     * @return array
     */
    public function getLogins(): array;

    /**
     * Récupération de l'instance de la requête Wordpress de récupération des utilisateurs.
     *
     * @return WP_User_Query
     */
    public function WpUserQuery(): WP_User_Query;
}