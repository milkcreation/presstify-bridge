<?php

namespace tiFy\Contracts\Routing;

use League\Route\RouteCollectionInterface;
use League\Route\RouteConditionHandlerInterface;
use League\Route\Middleware\MiddlewareAwareInterface;
use League\Route\Strategy\StrategyAwareInterface;
use Psr\Container\ContainerInterface;

interface RouteGroup extends
    MiddlewareAwareInterface,
    RouteCollectionInterface,
    RouteConditionHandlerInterface,
    RouteRegisterMapTrait,
    StrategyAwareInterface
{

    /**
     * Récupération du préfixe du groupe
     *
     * @return string
     */
    public function getPrefix() : string;

    /**
     * Instance du contrôleur d'injection de dépendances
     *
     * @return ContainerInterface
     */
    public function getContainer() : ContainerInterface;
}