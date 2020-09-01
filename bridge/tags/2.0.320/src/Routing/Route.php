<?php declare(strict_types=1);

namespace tiFy\Routing;

use FastRoute\RouteParser\Std as RouteParser;
use InvalidArgumentException;
use League\Route\{Route as LeagueRoute, RouteCollectionInterface, RouteGroup};
use LogicException;
use tiFy\Contracts\Http\RedirectResponse as HttpRedirect;
use tiFy\Contracts\Routing\Route as RouteContract;
use tiFy\Routing\Concerns\{ContainerAwareTrait, MiddlewareAwareTrait, RouteCollectionAwareTrait, StrategyAwareTrait};
use tiFy\Support\ParamsBag;
use tiFy\Support\Proxy\{Request, Redirect, Url};

class Route extends LeagueRoute implements RouteContract
{
    use ContainerAwareTrait, MiddlewareAwareTrait, RouteCollectionAwareTrait, StrategyAwareTrait;

    /**
     * Instance du controleur de gestion des routes.
     * @var RouteCollectionInterface
     */
    protected $collection;

    /**
     * Indicateur de route en réponse à la requête HTTP courante.
     * @var boolean
     */
    protected $current = false;

    /**
     * Instance des paramètres associés
     * @var ParamsBag|null
     */
    protected $params;

    /**
     * CONSTRUCTEUR.
     *
     * @param string $method
     * @param string $path
     * @param callable $handler
     * @param RouteCollectionInterface $collection Instance du controleur de gestion des routes.
     *
     * @return void
     */
    public function __construct(string $method, string $path, $handler, RouteCollectionInterface $collection)
    {
        $this->collection = $collection;

        parent::__construct($method, $path, $handler);
    }

    /**
     * @inheritDoc
     */
    public function getUrl(array $params = [], bool $absolute = false): string
    {
        $routes = (new RouteParser())->parse($this->collection->parseRoutePath($this->getPath()));
        $name = $this->name ?: __('Non qualifiée', 'tify');

        foreach ($routes as $segments) {
            $_params = $params;
            $url = '';
            $paramIdx = 0;

            foreach ($segments as $segment) {
                if (is_string($segment)) {
                    $url .= $segment;
                    continue;
                } elseif (isset($_params[$segment[0]])) {
                    $part = $_params[$segment[0]];
                    unset($_params[$segment[0]]);
                } elseif (isset($_params[$paramIdx])) {
                    $part = $_params[$paramIdx];
                    unset($_params[$paramIdx]);
                    $paramIdx++;
                } else {
                    throw new LogicException(sprintf(__(
                        'Url de la route invalide - Nombre de paramètres fournis insuffisants' .
                        ' >> Fournis : %s | Requis : %s | Route : %s.',
                        'tify'
                    ), json_encode($params), $segment[0], $name));
                }

                if (!preg_match("#{$segment[1]}+#", (string)$part)) {
                    throw new LogicException(sprintf(__(
                        'Url de la route invalide - Typage de paramètre incorrect' .
                        ' >> Fourni: %s | Attendu: %s | Route : %s.',
                        'tify'
                    ), $part, $segment[1], $name));
                } else {
                    $url .= $part;
                }
            }

            if ($absolute) {
                $host = $this->getHost() ?: Request::getHost();
                $port = $this->getPort() ?: Request::getPort();
                $scheme = $this->getScheme() ?: Request::getScheme();
                if ((($port === 80) && ($scheme = 'http')) || (($port === 443) && ($scheme = 'https'))) {
                    $port = '';
                }

                $url = $scheme . '://' . $host . ($port ? ':' . $port : '') . $url;
            }

            if (!empty($_params)) {
                return Url::set($url)->with($_params)->render();
            } else {
                return $url;
            }
        }

        throw new LogicException(sprintf(__(
            'Url de la route invalide - Génération impossible.' .
            ' >> Paramètres: %s | Route : %s.',
            'tify'
        ), json_encode($params), $name));
    }

    /**
     * @inheritDoc
     */
    public function getVar(string $key, $default = null)
    {
        return $this->vars[$key] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function isCurrent(): bool
    {
        return $this->current;
    }

    /**
     * @inheritDoc
     */
    public function params($key = null, $default = null)
    {
        if (!$this->params instanceof ParamsBag) {
            $this->params = new ParamsBag();
        }

        if (is_null($key)) {
            return $this->params;
        } elseif (is_string($key)) {
            return $this->params->get($key, $default);
        } elseif (is_array($key)) {
            return $this->params->set($key);
        } else {
            throw new InvalidArgumentException(
                __('La définition ou la récupération de paramètre de route est invalide', 'tify')
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function redirect(array $parameters = [], int $status = 302, array $headers = []): HttpRedirect
    {
        $url = $this->getUrl($parameters);

        return Redirect::to($url, $status, $headers);
    }

    /**
     * @inheritDoc
     */
    public function setCurrent()
    {
        $this->current = true;
    }

    /**
     * {@inheritDoc}
     *
     * @return LeagueRoute
     */
    public function setParentGroup(RouteGroup $group): LeagueRoute
    {
        $this->group = $group;

        return $this;
    }
}