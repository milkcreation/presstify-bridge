<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Driver\Pagination;

use tiFy\Contracts\Partial\PaginationQuery as PaginationQueryContract;
use tiFy\Partial\Driver\Pagination\PaginationQuery as BasePaginationQuery;
use WP_Query;

class PaginationQuery extends BasePaginationQuery
{
    /**
     * Instance de requête de récupération des post Wordpress.
     * @return WP_Query|null
     */
    protected $wpQuery;

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

            $this->wpQuery = $wp_query;
        } elseif ($args instanceof WP_Query) {
            $this->wpQuery = $args;
        } else {
            $this->wpQuery = new WP_Query($args);
        }
    }

    /**
     * @inheritDoc
     */
    public function setPagination(): PaginationQueryContract
    {
        $this->page = intval($this->wpQuery->get('paged', 1));

        $this->per_page = intval($this->wpQuery->get('posts_per_page', get_option('posts_per_page')));

        $this->offset = intval($this->wpQuery->get('offset', 0));

        $this->founds = intval($this->wpQuery->found_posts);

        return parent::setPagination();
    }
}