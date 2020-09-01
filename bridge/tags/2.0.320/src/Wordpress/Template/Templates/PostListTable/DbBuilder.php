<?php declare(strict_types=1);

namespace tiFy\Wordpress\Template\Templates\PostListTable;

use tiFy\Support\ParamsBag;
use tiFy\Template\Templates\ListTable\Contracts\DbBuilder as DbBuilderContract;
use tiFy\Template\Templates\PostListTable\DbBuilder as BaseDbBuilder;
use WP_Query;

class DbBuilder extends BaseDbBuilder
{
    /**
     * @inheritDoc
     */
    public function fetchItems(): DbBuilderContract
    {
        $this->parse();

        $args = $this->fetchQueryVars()->all();

        $query = new WP_Query($args);

        $total = (int)$query->found_posts;

        $items = $query->posts;

        if ($total < $this->getPerPage()) {
            $this->setPage(1);
        }

        $this->factory->items()->set($items);

        if ($count = count($items)) {
            $this->factory->pagination()
                ->setCount($count)
                ->setCurrentPage($this->getPage())
                ->setPerPage($this->getPerPage())
                ->setLastPage((int)ceil($total / $this->getPerPage()))
                ->setTotal($total)
                ->parse();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function fetchQueryVars(): ParamsBag
    {
        $qv = new ParamsBag();

        $qv->set('post_type', ($post_type = $this->get('post_type')) ? $post_type : 'any');

        if ($post_status = $this->get('post_status')) {
            $qv->set('post_status', $post_status);
        }

        if ($meta_query = $this->get('meta_query')) {
            $qv->set('meta_query', $meta_query);
        }

        if ($tax_query = $this->get('tax_query')) {
            $qv->set('tax_query', $tax_query);
        }

        if ($number = $this->getPerPage()) {
            $qv->set('posts_per_page', $this->getPerPage());
        }

        if ($paged = $this->getPage()) {
            $qv->set('paged', $this->getPage());
        }

        if ($order = $this->getOrder()) {
            $qv->set('order', $order);
        }

        if ($orderby = $this->getOrderBy()) {
            $qv->set('orderby', $orderby);
        }

        if ($search = $this->getSearch()) {
            $qv->set('s', $search);
        }

        return $qv;
    }
}