<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use tiFy\Contracts\Support\Collection;
use WP_Query;

interface QueryPosts extends Collection
{
    /**
     * Récupération d'une instance basée sur une liste des arguments.
     * @see https://codex.wordpress.org/Class_Reference/WP_Query
     * @see https://developer.wordpress.org/reference/classes/wp_query/
     *
     * @param array $args Liste des arguments de la requête récupération des éléments.
     *
     * @return static
     */
    public static function createFromArgs(array $args = []): QueryPosts;

    /**
     * Récupération d'une instance basée sur une resultat de requête en base de données.
     *
     * @param EloquentCollection $collection
     *
     * @return static
     */
    public static function createFromEloquent(EloquentCollection $collection): QueryPosts;

    /**
     * Récupération d'une instance basée sur la requête globale.
     * @see https://codex.wordpress.org/Class_Reference/WP_Query
     * @see https://developer.wordpress.org/reference/classes/wp_query/
     *
     * @return static
     */
    public static function createFromGlobals(): QueryPosts;

    /**
     * Récupération d'une instance basée sur une liste d'identifiant de qualification de posts.
     * @see https://codex.wordpress.org/Class_Reference/WP_Query
     * @see https://developer.wordpress.org/reference/classes/wp_query/
     *
     * @param int[] $ids
     *
     * @return static
     */
    public static function createFromIds(array $ids): QueryPosts;

    /**
     * Récupération de la liste des identifiants de qualification.
     *
     * @return array
     */
    public function getIds(): array;

    /**
     * Récupération de la liste des intitulés de qualification.
     *
     * @return array
     */
    public function getTitles(): array;

    /**
     * {@inheritDoc}
     *
     * @return QueryPosts
     */
    public function set($key, $value = null): Collection;

    /**
     * Récupération de l'instance de la requête Wordpress de récupération des posts.
     *
     * @return WP_Query|null
     */
    public function WpQuery(): ?WP_Query;
}