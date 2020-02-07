<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Pagination;

use tiFy\Contracts\Partial\PaginationUrl as PaginationUrlContract;
use tiFy\Contracts\Routing\UrlFactory;
use tiFy\Support\Proxy\Url;

class PaginationUrl implements PaginationUrlContract
{
    /**
     * Instance de l'url de base.
     * @var UrlFactory
     */
    protected $baseurl;

    /**
     * CONSTRUCTEUR.
     *
     * @param UrlFactory|string|null $baseurl
     *
     * @return void
     */
    public function __construct($baseurl = null)
    {
        $this->baseurl = $baseurl ?: Url::without(['page']);

        if (!$this->baseurl instanceof UrlFactory) {
            $this->baseurl = Url::set($this->baseurl);
        }
    }

    /**
     * @inheritDoc
     */
    public function page(int $num): string
    {
        $url = clone $this->baseurl;
        $decoded = $url->decoded();

        if (preg_match('/%d/', $url->decoded())) {
            return sprintf($decoded, $num);
        } else {
            return $num > 1 ? sprintf($url->with(['page' => '%d'])->decoded(), $num) : $decoded;
        }
    }
}