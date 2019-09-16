<?php

namespace tiFy\Partial\Partials\Pagination;

use tiFy\Contracts\Routing\UrlFactory;

class PaginationUrl
{
    /**
     * Url de base.
     * @var UrlFactory
     */
    protected $baseurl = '';

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct($baseurl = null)
    {
        $this->baseurl = $baseurl ?: url_factory(url()->full())->without(['page']);

        if (!$this->baseurl instanceof UrlFactory) :
            $this->baseurl = url_factory($this->baseurl);
        endif;
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
            ? sprintf($url->with(['page' => '%d'])->getDecode(), $num)
            : $url->getDecode();
    }
}