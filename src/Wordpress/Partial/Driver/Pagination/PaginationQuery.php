<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Driver\Pagination;

use tiFy\Partial\Driver\Pagination\PaginationQuery as BasePaginationQuery;
use WP_Query;

class PaginationQuery extends BasePaginationQuery
{
    /**
     * CONSTRUCTEUR.
     *
     * @param array|WP_Query|null $args Liste des arguments de requête|Requête de récupération des éléments.
     *
     * @return void
     */
    public function __construct($args = null)
    {
        if (is_null($args)) {
            global $wp_query;

            $args = $wp_query;
        }

        $args = $args instanceof WP_Query ? $this->wpQueryArgs($args) : $args;

        parent::__construct($args);
    }

    /**
     * @inheritDoc
     */
    public function wpQueryArgs(WP_Query $wp_query): array
    {
        $total = (int)$wp_query->found_posts;
        $per_page = (int)$wp_query->get('posts_per_page');
        $current = $wp_query->get('paged') ?: 1;

        return [
            'count'        => (int)$wp_query->post_count,
            'current_page' => $per_page < 0 ? 1 : (int)$current,
            'last_page'    => $per_page < 0 ? 1 : (int)$wp_query->max_num_pages,
            'per_page'     => $per_page,
            'query_obj'    => $wp_query,
            'total'        => $total,
        ];
    }
}