<?php declare(strict_types=1);

namespace tiFy\Routing\Concerns;

use League\Route\Route as LeagueRoute;
use tiFy\Contracts\Routing\Route as RouteContract;

trait RouteCollectionAwareTrait
{
    /**
     * @inheritDoc
     *
     * @return static
     */
    public function map(string $method, string $path, $handler): LeagueRoute
    {
        return parent::map($method, $path, $handler);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function get($path, $handler): LeagueRoute
    {
        return parent::get($path, $handler);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function post($path, $handler): LeagueRoute
    {
        return parent::post($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function put($path, $handler): LeagueRoute
    {
        return parent::put($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function patch($path, $handler): LeagueRoute
    {
        return parent::patch($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function delete($path, $handler): LeagueRoute
    {
        return parent::delete($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function head($path, $handler): LeagueRoute
    {
        return parent::head($path, $handler);
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function options($path, $handler): LeagueRoute
    {
        return parent::options($path, $handler);
    }

    /**
     * Déclaration d'une route dédiée aux requêtes Ajax XmlHttpRequest (Xhr).
     *
     * @param string $path Chemin relatif vers la route.
     * @param string|callable $handler Traitement de la route.
     * @param string $method Méthode de la requête.
     *
     * @return static
     */
    public function xhr(string $path, $handler, string $method = 'POST'): RouteContract
    {
        return $this->map(strtoupper($method), '/' . ltrim($path, '/'), $handler)
            ->strategy('json')->middleware('xhr');
    }
}