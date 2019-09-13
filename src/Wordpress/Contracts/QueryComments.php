<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts;

use tiFy\Contracts\Support\Collection;
use WP_Comment_Query;

interface QueryComments extends Collection
{
    /**
     * Création d'une instance basée sur une liste d'arguments.
     * @see https://codex.wordpress.org/Function_Reference/get_comment
     *
     * @param array $args Liste des arguments de récupération de la liste des commentaires.
     *
     * @return static
     */
    public static function createFromArgs(array $args): QueryComments;

    /**
     * Création d'une instance basée sur une liste d'identifiants.
     *
     * @param int[] $ids Liste des identifiants de commentaire.
     *
     * @return static
     */
    public static function createFromIds(array $ids): QueryComments;

    /**
     * Récupération de la liste des identifiants de qualification.
     *
     * @return int[]
     */
    public function getIds(): array;

    /**
     * Récupération l'instance de requête Wordpress de récupération des commentaires.
     *
     * @return WP_Comment_Query
     */
    public function WpCommentQuery(): WP_Comment_Query;
}