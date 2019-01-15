<?php

namespace tiFy\Contracts\Routing;

use League\Route\Strategy\StrategyInterface;

interface RouteRegisterMapTrait
{
    /**
     * Déclaration d'une route.
     *
     * @param string $name Identifiant de qualification de la route.
     * @param array $attrs {
     *  Attributs de configuration.
     *
     *  @var string $method Méthode de traitement de la requête. GET|POST|PUT|PATCH|DELETE|HEAD|OPTIONS.
     *      @var string $path Chemin relatif.
     *      @var callable $cb
     *      @var string $scheme Condition de traitement du schema de l'url. http|https.
     *      @var string $host Condition de traitement relative au domaine. ex. example.com.
     *      @var string|StrategyInterface $strategy Controleur de traitement de la route répondant à la requête HTTP courante. html|json|StrategyInterface.
     * }
     *
     * @return Route
     */
    public function register($name, $attrs = []);
}