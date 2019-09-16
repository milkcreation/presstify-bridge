<?php declare(strict_types=1);

namespace tiFy\Routing;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Container\ContainerInterface as Container;

abstract class BaseMiddleware implements MiddlewareInterface
{
    /**
     * Instance de conteneur d'injection de dépendances.
     * @var Container
     */
    protected $container;

    /**
     * CONSTRUCTEUR.
     *
     * @param Container $container Instance de conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?Container $container)
    {
        $this->container = $container;

        $this->boot();
    }

    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function boot(): void
    {

    }
}