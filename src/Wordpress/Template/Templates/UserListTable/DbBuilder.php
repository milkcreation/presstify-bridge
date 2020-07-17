<?php declare(strict_types=1);

namespace tiFy\Wordpress\Template\Templates\UserListTable;

use tiFy\Support\ParamsBag;
use tiFy\Template\Templates\ListTable\Contracts\DbBuilder as DbBuilderContract;
use tiFy\Template\Templates\UserListTable\DbBuilder as BaseDbBuilder;
use WP_User_Query;

class DbBuilder extends BaseDbBuilder
{
    /**
     * @inheritDoc
     */
    public function fetchItems(): DbBuilderContract
    {
        $this->parse();

        $query = new WP_User_Query($this->fetchQueryVars()->all());

        $total = $query->get_total();

        $items = $query->get_results();

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

        if ($roles = $this->get('roles', [])) {
            $qv->set('role__in', $roles);
        }

        if ($number = $this->getPerPage()) {
            $qv->set('number', $this->getPerPage());
        }

        if ($paged = $this->getPage()) {
            $qv->set('offset', ($this->getPage()-1)*$this->getPerPage());
        }

        if ($order = $this->getOrder()) {
            $qv->set('order', $order);
        }

        if ($orderby = $this->getOrderBy()) {
            $qv->set('orderby', $orderby);
        }

        if ($search = $this->getSearch()) {
            $qv->set('search_columns', [
                'user_login',
                'user_email',
                'user_nicename',
                'display_name'
            ]);
            $qv->set('search', "*{$search}*");
        }

        return $qv;
    }
}