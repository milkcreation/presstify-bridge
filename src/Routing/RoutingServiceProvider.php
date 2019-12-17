<?php declare(strict_types=1);

namespace tiFy\Routing;

use tiFy\Contracts\Routing\{Route as RouteContract, RouteGroup as RouteGroupContract};
use tiFy\Container\ServiceProvider;
use tiFy\Routing\{
    Middleware\Xhr,
    Strategy\App,
    Strategy\Json};
use Zend\Diactoros\ResponseFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

class RoutingServiceProvider extends ServiceProvider
{
    /**
     * Liste des services fournis.
     * @var array
     */
    protected $provides = [
        'router',
        RouteContract::class,
        RouteGroupContract::class,
        'router.emitter',
        'router.middleware.xhr',
        'router.strategy.app',
        'router.strategy.default',
        'router.strategy.json',
        'redirect',
        'url'
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->registerEmitter();
        $this->registerMiddleware();
        $this->registerRedirect();
        $this->registerRouter();
        $this->registerStrategies();
        $this->registerUrl();
    }

    /**
     * Déclaration du contrôleur d'émission de la réponse HTTP.
     *
     * @return void
     */
    public function registerEmitter(): void
    {
        $this->getContainer()->share('router.emitter', function () {
            return new SapiEmitter();
        });
    }

    /**
     * Déclaration des Middlewares.
     *
     * @return void
     */
    public function registerMiddleware(): void
    {
        $this->getContainer()->add('router.middleware.xhr', function () {
            return new Xhr();
        });
    }

    /**
     * Déclaration du controleur de redirection.
     *
     * @return void
     */
    public function registerRedirect(): void
    {
        $this->getContainer()->add('redirect', function () {
            return new Redirector($this->getContainer()->get('router'));
        });
    }

    /**
     * Déclaration du controleur de routage.
     *
     * @return void
     */
    public function registerRouter(): void
    {
        $this->getContainer()->share('router', function () {
            return (new Router())->setContainer($this->getContainer());
        });

        $this->getContainer()->add(
            RouteContract::class,
            function (string $method, string $path, callable $handler, $collection) {
                return new Route($method, $path, $handler, $collection);
            });

        $this->getContainer()->add(
            RouteGroupContract::class,
            function (string $prefix, callable $handler, $collection) {
                return new RouteGroup($prefix, $handler, $collection);
            });
    }

    /**
     * Déclaration des controleurs de strategies.
     *
     * @return void
     */
    public function registerStrategies(): void
    {
        $this->getContainer()->add('router.strategy.default', function () {
            return new App();
        });

        $this->getContainer()->add('router.strategy.app', function () {
            return new App();
        });

        $this->getContainer()->add('router.strategy.json', function () {
            return new Json(new ResponseFactory());
        });
    }

    /**
     * Déclaration du controleur d'urls.
     *
     * @return void
     */
    public function registerUrl(): void
    {
        $this->getContainer()->share('url', function () {
            return new Url($this->getContainer()->get('router'), request());
        });
    }
}