<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Driver\Pagination;

use tiFy\Contracts\Partial\{
    PaginationQuery as PaginationQueryContract,
    PartialDriver as BasePartialDriverContract
};
use tiFy\Partial\Driver\Pagination\{Pagination as PaginationBase};
use tiFy\Wordpress\Contracts\Partial\PartialDriver as PartialDriverContract;

class Pagination extends PaginationBase implements PartialDriverContract
{
    /**
     * @inheritDoc
     */
    public function parseQuery(): BasePartialDriverContract
    {
        $this->query = $this->pull('query', null);
        if (!$this->query instanceof PaginationQueryContract) {
            $this->query = new PaginationQuery($this->query);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseUrl(): BasePartialDriverContract
    {
        if ($this->has('url.base')) {
            $this->query->setBaseUrl($this->get('url.base', null));
        }

        if ($this->has('url.segment')) {
            $this->query->setSegmentUrl($this->get('url.segment'));
        } else {
            $this->query->setSegmentUrl(true);
        }

        if ($this->has('url.index')) {
            $this->query->setPageIndex($this->get('url.index'));
        }

        if (!is_array($this->get('url'))) {
            $this->query->setBaseUrl($this->get('url', null));
        }

        return $this;
    }
}