<?php declare(strict_types=1);

namespace tiFy\Routing;

use Psr\Container\ContainerInterface as Container;
use Psr\Http\Server\MiddlewareInterface;

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
     * @param Container|null $container Instance de conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        $this->container = $container;

        $this->boot();
    }

    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function boot(): void { }
}