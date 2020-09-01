<?php declare(strict_types=1);

namespace tiFy\Contracts\Routing;

use InvalidArgumentException;
use League\Route\Route as LeagueRoute;
use League\Route\RouteGroup;
use League\Route\Middleware\MiddlewareAwareInterface;
use League\Route\Strategy\StrategyAwareInterface;
use League\Route\RouteConditionHandlerInterface;
use LogicException;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use tiFy\Contracts\Http\{RedirectResponse as HttpRedirect};
use tiFy\Contracts\Support\ParamsBag;

/**
 * Interface Route
 * @package tiFy\Contracts\Routing
 *
 * @mixin LeagueRoute
 */
interface Route extends
    ContainerAwareTrait,
    MiddlewareInterface,
    MiddlewareAwareInterface,
    RouteConditionHandlerInterface,
    StrategyAwareInterface,
    StrategyAwareTrait
{
    /**
     * Récupération du controleur de traitement.
     *
     * @param ContainerInterface|null $container
     *
     * @return callable
     *
     * @throws InvalidArgumentException
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
     * @throws LogicException
     */
    public function getUrl(array $params = [], bool $absolute = false): string;

    /**
     * Récupération de variable d'url de la route.
     *
     * @param string $key Clé d'indice de la variable.
     * @param string $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getVar(string $key, $default = null);

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
     * Définition d'un ou plusieurs middlewares associés.
     *
     * @param string|string[]|MiddlewareInterface|MiddlewareInterface[] $middleware
     *
     * @return static
     */
    public function middleware($middleware): MiddlewareAwareInterface;

    /**
     * Définition de paramètre|Récupération de paramètres|Récupération de l'instance des paramètres.
     *
     * @param array|string|null $key Liste des définitions de paramètres|Indice de qualification du paramètres à récupérer (Syntaxe à point permise).
     * @param mixed $default Valeur de retour par défaut lors de la récupération de paramètres.
     *
     * @return mixed|ParamsBag
     */
    public function params($key = null, $default = null);

    /**
     * Création d'une instance de reponse de redirection PSR.
     *
     * @param array $parameters Liste des paramètres passés en argument dans l'url de la route.
     * @param int $status Code du statut de redirection.
     * @param array $headers Liste des entêtes complémentaires.
     *
     * @return HttpRedirect
     */
    public function redirect(array $parameters = [], int $status = 302, array $headers = []): HttpRedirect;

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
     * @return LeagueRoute|static
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