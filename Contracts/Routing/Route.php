<?php

namespace tiFy\Contracts\Routing;

use League\Route\Route as LeagueRoute;
use League\Route\RouteGroup;
use League\Route\Middleware\MiddlewareAwareInterface;
use League\Route\Strategy\StrategyAwareInterface;
use League\Route\RouteConditionHandlerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

interface Route extends
    MiddlewareInterface,
    MiddlewareAwareInterface,
    RouteConditionHandlerInterface,
    StrategyAwareInterface
{
    /**
     * Récupération du controleur de traitement.
     *
     * @param ContainerInterface|null $container
     *
     * @throws \InvalidArgumentException
     *
     * @return callable
     */
    public function getCallable(?ContainerInterface $container = null): callable;

    /**
     * Récupération de la méthode de traitement de la requête HTTP associée.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Récupération du chemin relatif associé à la route.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Récupération du groupe parent.
     *
     * @return RouteGroup
     */
    public function getParentGroup(): ?RouteGroup;

    /**
     * Récupération de l'url associée.
     *
     * @param array $params Liste des variables passée en argument. Tableau indexé.
     * @param boolean $absolute Activation de la récupération de l'url absolue.
     *
     * @return string
     *
     * @throws \LogicException
     */
    public function getUrl(array $params = [], bool $absolute = true): string;

    /**
     * Récupération de la liste des arguments passée dans la requête HTTP courante.
     *
     * @return array
     */
    public function getVars();

    /**
     * Vérifie si la route répond à la requête HTTP courante.
     *
     * @return boolean
     */
    public function isCurrent(): bool;

    /**
     * Définition de l'indicateur de route en réponse à la requête courante.
     *
     * @return void
     */
    public function setCurrent();

    /**
     * Définition du groupe parent.
     *
     * @param RouteGroup $group
     *
     * @return static
     */
    public function setParentGroup(RouteGroup $group): LeagueRoute;

    /**
     * Définition de la liste des variables passées en argument dans la requête HTTP courante.
     *
     * @param array $args Liste des variables.
     *
     * @return void
     */
    public function setVars(array $args);
}