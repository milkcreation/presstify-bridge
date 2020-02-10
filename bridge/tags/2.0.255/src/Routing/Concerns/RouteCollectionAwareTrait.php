<?php declare(strict_types=1);

namespace tiFy\Routing\Concerns;

use League\Route\Route as LeagueRoute;
use tiFy\Contracts\Routing\Route as RouteContract;
use tiFy\Routing\Middleware\Xhr;

trait RouteCollectionAwareTrait
{
    /**
     * {@inheritdoc}
     *
     * @return RouteContract
     */
    public function get($path, $handler): LeagueRoute
    {
        return parent::get($path, $handler);
    }

    /**
     * {@inheritdoc}
     *
     * @return RouteContract
     */
    public function post($path, $handler): LeagueRoute
    {
        return parent::post($path, $handler);
    }

    /**
     * Déclaration d'une route dédiée aux requêtes Ajax XmlHttpRequest (Xhr).
     *
     * @param string $path Chemin relatif vers la route.
     * @param string|callable $handler Traitement de la route.
     * @param string $method Méthode de la requête.
     *
     * @return RouteContract
     */
    public function xhr(string $path, $handler, string $method = 'POST'): RouteContract
    {
        return $this->map(strtoupper($method), '/' . ltrim($path, '/'), $handler)
            ->strategy('json')->middleware(new Xhr());
    }
}