<?php declare(strict_types=1);

namespace tiFy\Routing;

use Laminas\Diactoros\ResponseFactory;
use tiFy\Contracts\Routing\{Route as RouteContract, RouteGroup as RouteGroupContract};
use tiFy\Container\ServiceProvider;
use tiFy\Routing\{
    Middleware\CookieMiddleware,
    Middleware\XhrMiddleware,
    Strategy\ApiStrategy,
    Strategy\AppStrategy,
    Strategy\JsonStrategy
};

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
        'router.strategy.api',
        'router.strategy.app',
        'router.strategy.default',
        'router.strategy.json',
        'redirect',
        'url',
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->registerEmitter();
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
            return new Emitter($this->getContainer()->get('router'));
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
            return (new Router())
                ->setContainer($this->getContainer())
                ->setMiddlewareStack([
                    'cookie' => new CookieMiddleware(),
                    'xhr'    => new XhrMiddleware(),
                ])
                ->middleware('cookie');
        });

        $this->getContainer()->add(
            RouteContract::class,
            function (string $method, string $path, callable $handler, $collection) {
                return (new Route($method, $path, $handler, $collection))->setContainer($this->getContainer());
            });

        $this->getContainer()->add(
            RouteGroupContract::class,
            function (string $prefix, callable $handler, $collection) {
                return (new RouteGroup($prefix, $handler, $collection))->setContainer($this->getContainer());
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
            return new AppStrategy();
        });

        $this->getContainer()->add('router.strategy.api', function () {
            return new ApiStrategy(new ResponseFactory());
        });

        $this->getContainer()->add('router.strategy.app', function () {
            return new AppStrategy();
        });

        $this->getContainer()->add('router.strategy.json', function () {
            return new JsonStrategy(new ResponseFactory());
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
            return new Url($this->getContainer()->get('router'));
        });
    }
}