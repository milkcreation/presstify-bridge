<?php declare(strict_types=1);

namespace tiFy\Routing;

use FastRoute\RouteParser\Std as RouteParser;
use InvalidArgumentException;
use League\Route\Route as LeagueRoute;
use LogicException;
use tiFy\Contracts\Routing\{Route as RouteContract, Router as RouterContract, UrlFactory as UrlFactoryContract};
use tiFy\Routing\Concerns\{ContainerAwareTrait, StrategyAwareTrait};
use tiFy\Support\ParamsBag;

class Route extends LeagueRoute implements RouteContract
{
    use ContainerAwareTrait, StrategyAwareTrait;

    /**
     * Instance du controleur de gestion des routes.
     * @var RouterContract
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
     * @param Router $collection Instance du controleur de gestion des routes.
     *
     * @return void
     */
    public function __construct(string $method, string $path, $handler, $collection)
    {
        $this->collection = $collection;

        parent::__construct(strtoupper($method), $path, $handler);

        $this->setContainer($this->collection->getContainer());
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(array $params = [], bool $absolute = false): string
    {
        $routes = (new RouteParser())->parse($this->collection->parseRoutePath($this->getPath()));

        foreach ($routes as $route) {
            $url = '';
            $paramIdx = 0;
            foreach ($route as $part) {
                if (is_string($part)) {
                    $url .= $part;
                    continue;
                } elseif ($paramIdx === count($params)) {
                    throw new LogicException(__('Le nombre de paramètres fournis est insuffisant.', 'tify'));
                }

                $url .= $params[$paramIdx++];
            }

            if ($paramIdx === count($params)) {
                if ($absolute) {
                    $host = $this->getHost() ?: request()->getHost();
                    $port = $this->getPort() ?: request()->getPort();
                    $scheme = $this->getScheme() ?: request()->getScheme();
                    if ((($port === 80) && ($scheme = 'http')) || (($port === 443) && ($scheme = 'https'))) {
                        $port = '';
                    }

                    $url = $scheme . '://' . $host . ($port ? ':' . $port : '') . $url;
                }

                return $url;
            }
        }

        throw new LogicException(__('Le nombre de paramètres fournis est trop important.', 'tify'));
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
    public function setCurrent()
    {
        $this->current = true;
    }
}