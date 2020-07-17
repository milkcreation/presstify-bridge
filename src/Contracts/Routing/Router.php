<?php declare(strict_types=1);

namespace tiFy\Contracts\Routing;

use ArrayAccess;
use Countable;
use Closure;
use Illuminate\Support\Collection;
use IteratorAggregate;
use League\Route\Middleware\MiddlewareAwareInterface;
use League\Route\RouteCollectionInterface;
use League\Route\Route as LeagueRoute;
use League\Route\Strategy\StrategyAwareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\{ResponseInterface,
    ResponseInterface as Response,
    ServerRequestInterface};
use Psr\Http\Server\MiddlewareInterface;
use tiFy\Contracts\Container\Container;
use tiFy\Routing\BaseController;
use Symfony\Component\HttpFoundation\Response as SfResponse;

interface Router extends
    ArrayAccess,
    Countable,
    ContainerAwareTrait,
    IteratorAggregate,
    MiddlewareAwareInterface,
    RouteCollectionInterface,
    RouteCollectionAwareTrait,
    StrategyAwareInterface
{
    /**
     * Create a route group with a common prefix.
     *
     * All routes created in the passed callback will have the given group prefix prepended.
     *
     * @param string $prefix
     * @param callable $callback
     */
    public function addGroup($prefix, callable $callback);

    /**
     * Adds a route to the collection.
     *
     * The syntax used in the $route string depends on the used route parser.
     *
     * @param string|string[] $httpMethod
     * @param string $route
     * @param mixed $handler
     */
    public function addRoute($httpMethod, $route, $handler);

    /**
     * Récupération de la liste des routes déclarés.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Récupération de la collection des routes déclarées.
     *
     * @return Collection
     */
    public function collect(): Collection;

    /**
     * Compte de le nombre de routes déclarées.
     *
     * @return int
     */
    public function count(): int;

    /**
     * Récupération de la route courante associée à la requête HTTP.
     *
     * @return Route|null
     */
    public function current(): ?Route;

    /**
     * Récupération du nom de qualification de la route courante associée à la requête HTTP.
     *
     * @return string|null
     */
    public function currentRouteName(): ?string;

    /**
     * Dispatch the route based on the request.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request): ResponseInterface;

    /**
     * Récupération de l'instance de la réponse HTTP.
     *
     * @return Response|null
     */
    public function getResponse(): ?Response;

    /**
     * Emission de la réponse.
     *
     * @param ResponseInterface|SfResponse $response Réponse HTTP.
     *
     * @return Response
     */
    public function emit($response): Response;

    /**
     * Vérification d'existance de routes déclarées.
     *
     * @param string Nom de qualification de la route.
     *
     * @return boolean
     */
    public function exists(): bool;

    /**
     * Returns the collected route data, as provided by the data generator.
     *
     * @return array
     */
    public function getData();

    /**
     * {@inheritDoc}
     *
     * @return Container|null
     */
    public function getContainer(): ?ContainerInterface;

    /**
     * Récupération de l'instance d'un controleur qualifié déclaré.
     *
     * @param string $name
     *
     * @return string|array|Closure|callable|BaseController|null
     */
    public function getNamedController(string $name);

    /**
     * Récupération de l'instance d'un middleware qualifié déclaré.
     *
     * @param string $name
     *
     * @return MiddlewareInterface|null
     */
    public function getNamedMiddleware(string $name): ?MiddlewareInterface;

    /**
     * Récupération d'une route déclarée selon son nom de qualification.
     *
     * @param string $name Nom de qualification.
     *
     * @return Route|LeagueRoute
     */
    public function getNamedRoute(string $name): LeagueRoute;

    /**
     * Add a group of routes to the collection.
     *
     * @param string $prefix
     * @param callable $group
     *
     * @return RouteGroup
     */
    public function group(string $prefix, callable $group);

    /**
     * Vérification d'existance d'une route associée à la requête HTTP courante.
     *
     * @return boolean
     */
    public function hasCurrent();

    /**
     * Vérification si le nom de qualification correspond à la route déclarée.
     *
     * @param string $name Nom de qualification de la route.
     *
     * @return boolean
     */
    public function hasNamedRoute(string $name): bool;

    /**
     * Vérification si le nom de qualification correspond à la route associée à la requête HTTP courante.
     *
     * @param string $name Nom de qualification à contrôler.
     *
     * @return boolean
     */
    public function isCurrentNamed(string $name): bool;

    /**
     * Déclaration d'un controleur qualifié.
     *
     * @param string $name Nom de qualification
     * @param string|array|Closure|callable|BaseController $controller
     *
     * @return string|array|Closure|callable|BaseController
     */
    public function registerController(string $name, BaseController $controller);

    /**
     * Déclaration d'un middleware qualifié.
     *
     * @param string $name Nom de qualification
     * @param MiddlewareInterface $middleware
     *
     * @return MiddlewareInterface
     */
    public function registerMiddleware(string $name, MiddlewareInterface $middleware): MiddlewareInterface;

    /**
     * Déclaration d'un jeu de controleurs qualifiés.
     *
     * @param string[]|array[]|Closure[]|callable[]|BaseController[] $controllers
     *
     * @return static
     */
    public function setControllerStack(array $controllers): Router;

    /**
     * Déclaration d'un jeu de middlewares qualifiés.
     *
     * @param MiddlewareInterface[] $middlewares
     *
     * @return static
     */
    public function setMiddlewareStack(array $middlewares): Router;

    /**
     * Définition du préfixe du chemin des routes.
     *
     * @param string|null $prefix
     *
     * @return $this
     */
    public function setPrefix(?string $prefix = null): Router;

    /**
     * Récupération de l'url d'une route.
     *
     * @param string $name Nom de qualification.
     * @param array $parameters Liste des variables passées en argument.
     * @param boolean $absolute Activation de l'url absolue.
     * @param boolean $asserts Activation des exceptions.
     *
     * @return string
     */
    public function url(string $name, array $parameters = [], bool $absolute = false, bool $asserts = false);
}