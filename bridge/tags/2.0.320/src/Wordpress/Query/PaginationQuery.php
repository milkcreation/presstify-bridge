<?php declare(strict_types=1);

namespace tiFy\Wordpress\Query;

use tiFy\Support\{ParamsBag, Traits\PaginationAwareTrait, Proxy\Partial};
use tiFy\Wordpress\Contracts\Query\PaginationQuery as PaginationQueryContract;

class PaginationQuery extends ParamsBag implements PaginationQueryContract
{
    use PaginationAwareTrait;

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * {@inheritDoc}
     *
     * @return PaginationQueryContract
     */
    public function parse(): PaginationQueryContract
    {
        parent::parse();

        if ($baseUrl = $this->pull('base_url')) {
            $this->setBaseUrl($baseUrl);
        }

        if ($count = $this->pull('count')) {
            $this->setCount($count);
        }

        if ($currentPage = $this->pull('current_page')) {
            $this->setCurrentPage($currentPage);
        }

        if ($lastPage = $this->pull('last_page')) {
            $this->setLastPage($lastPage);
        }

        if ($pageIndex = $this->pull('page_index')) {
            $this->setPageIndex($pageIndex);
        }

        if ($per_page = $this->pull('per_page')) {
            $this->setPerPage($per_page);
        }

        if ($segmentUrl = $this->pull('segment_url', false)) {
            $this->setSegmentUrl($segmentUrl);
        }

        if ($total = $this->pull('total')) {
            $this->setTotal($total);
        }

        if ($offset = $this->pull('offset')) {
            $this->setOffset($offset);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return Partial::get('pagination', ['query' => $this])->render();
    }
}