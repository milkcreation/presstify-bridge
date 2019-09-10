<?php

namespace tiFy\Partial\Partials\Pagination;

use tiFy\Kernel\Collection\QueryCollection;
use WP_Query;

class PaginationQuery extends QueryCollection
{
    /**
     * Nombre d'éléments de décalage.
     * @var int
     */
    protected $offset = 0;

    /**
     * Numéro de page courante.
     * @var int
     */
    protected $page = 0;

    /**
     * Nombre d'éléments par page.
     * @var int
     */
    protected $per_page = 10;

    /**
     * Nombre total de page.
     * @var int
     */
    protected $total_page = 0;

    /**
     * CONSTRUCTEUR.
     *
     * @param array|WP_Query $args Liste des arguments de requête|Requête de récupération des éléments.
     *
     * @return void
     */
    public function __construct($args)
    {
        $this->query($args);
    }

    /**
     * @inheritdoc
     */
    public function query($args)
    {
        if (empty($args)) :
            /** @var WP_Query $wp_query */
            global $wp_query;
        elseif ($args instanceof WP_Query) :
            $wp_query = $args;
        else :
            $wp_query = new WP_Query($args);
        endif;

        $this->page = intval($wp_query->get('paged', 1));

        $this->per_page = intval($wp_query->get('posts_per_page', get_option('posts_per_page')));

        $this->offset = intval($wp_query->get('offset', 0));

        $this->founds = intval($wp_query->found_posts);

        if ($this->founds) :
            $this->total_page = $this->offset
                ? ceil(
                    ($this->founds + (($this->per_page * ($this->page - 1)) - $this->offset)) / $this->per_page
                )
                : ceil($this->founds / $this->per_page);
        else :
            $this->total_page = 0;
        endif;
    }

    /**
     * @inheritdoc
     */
    public function getPage()
    {
        return $this->page ? : 1;
    }

    /**
     * @inheritdoc
     */
    public function getTotalPage()
    {
        return $this->total_page;
    }
}