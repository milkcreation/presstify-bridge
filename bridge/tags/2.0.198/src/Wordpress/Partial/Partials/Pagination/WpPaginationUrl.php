<?php

namespace tiFy\Wordpress\Partial\Partials\Pagination;

use tiFy\Partial\Partials\Pagination\PaginationUrl;

class WpPaginationUrl extends PaginationUrl
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct($baseurl = null)
    {
        $baseurl = $baseurl ? : url_factory(url()->full())->deleteSegments('/page/\d+');

        parent::__construct($baseurl);
    }

    /**
     * Récupération du lien vers une page via son numéro.
     *
     * @param int $num Numéro de la page.
     *
     * @return string
     */
    public function page($num)
    {
        $url = clone $this->baseurl;

        return $num > 1
            ? sprintf($url->appendSegment('/page/%d')->getDecode(), $num)
            : $url->getDecode();
    }
}