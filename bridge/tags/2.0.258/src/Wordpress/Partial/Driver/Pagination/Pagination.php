<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Driver\Pagination;

use tiFy\Contracts\Partial\{
    PaginationQuery as PaginationQueryContract,
    PaginationUrl as PaginationUrlContract,
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
        $this->query = $this->get('query', []);
        if (!$this->query instanceof PaginationQueryContract) {
            $this->query = new PaginationQuery($this->query);
        }
        $this->set('query', $this->query->setPagination());

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseUrl(): BasePartialDriverContract
    {
        $this->url = $this->get('url', []);
        if (!$this->url instanceof PaginationUrlContract) {
            $this->url = new PaginationUrl($this->url);
        }
        $this->set('url', $this->url);

        return $this;
    }
}