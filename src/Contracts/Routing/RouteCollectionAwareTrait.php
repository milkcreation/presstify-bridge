<?php declare(strict_types=1);

namespace tiFy\Contracts\Routing;

use tiFy\Contracts\Routing\Route as RouteContract;

interface RouteCollectionAwareTrait
{
    /**
     * Déclaration d'une route dédiée aux requêtes Ajax XmlHttpRequest (Xhr).
     *
     * @param string $path Chemin relatif vers la route.
     * @param string|callable $handler Traitement de la route.
     * @param string $method Méthode de la requête.
     *
     * @return RouteContract
     */
    public function xhr(string $path, $handler, string $method = 'POST'): RouteContract;
}