<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\Pagination;

use tiFy\Contracts\Routing\UrlFactory;
use tiFy\Partial\Partials\Pagination\PaginationUrl;
use tiFy\Support\Proxy\Url;

class WpPaginationUrl extends PaginationUrl
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
     * Récupération du lien vers une page via son numéro.
     *
     * @param int $num Numéro de la page.
     *
     * @return string
     */
    public function page($num): string
    {
        $url = clone $this->baseurl;

        return $num > 1 ? sprintf($url->appendSegment('/page/%d')->decoded(), $num) : $url->decoded();
    }
}