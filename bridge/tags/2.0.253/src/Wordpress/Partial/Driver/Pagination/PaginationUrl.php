<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Driver\Pagination;

use tiFy\Contracts\Routing\UrlFactory;
use tiFy\Partial\Driver\Pagination\PaginationUrl as BasePaginationUrl;
use tiFy\Support\Proxy\Url;

class PaginationUrl extends BasePaginationUrl
{
    /**
     * CONSTRUCTEUR.
     *
     * @param UrlFactory|string|null $baseurl
     *
     * @return void
     */
    public function __construct($baseurl = null)
    {
        $baseurl = $baseurl ?: Url::deleteSegment('/page/\d+');

        parent::__construct($baseurl);
    }

    /**
     * @inheritDoc
     */
    public function page(int $num): string
    {
        $url = clone $this->baseurl;

        return $num > 1 ? sprintf($url->appendSegment('/page/%d')->decoded(), $num) : $url->decoded();
    }
}