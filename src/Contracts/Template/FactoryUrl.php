<?php declare(strict_types=1);

namespace tiFy\Contracts\Template;

use tiFy\Contracts\Routing\Url;

interface FactoryUrl extends FactoryAwareTrait, Url
{
    /**
     * Url de requête HTTP.
     *
     * @param bool $absolute
     *
     * @return string
     */
    public function http(bool $absolute = false): string;

    /**
     * Définition de chemin des requêtes HTTP et XHR.
     *
     * @param string $base_url
     *
     * @return static
     */
    public function setBaseUrl(string $base_url): FactoryUrl;

    /**
     * Url de requête XHR.
     *
     * @param bool $absolute
     *
     * @return string
     */
    public function xhr(bool $absolute = false): string;
}