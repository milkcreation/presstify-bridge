<?php

namespace tiFy\Routing;

use Http\Factory\Diactoros\ResponseFactory;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\JsonStrategy;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use tiFy\Container\ServiceProvider;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

class RoutingServiceProvider extends ServiceProvider
{
    /**
     * Liste des services fournis.
     * @var array
     */
    protected $provides = [
        'router',
        'router.emitter',
        'router.strategy.default',
        'router.strategy.json',
        ServerRequestInterface::class,
        'url',
        'url.factory'
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->registerRouter();
        $this->registerUrl();
        $this->registerPsrRequest();
        $this->registerEmitter();
        $this->registerStrategies();
    }

    /**
     * Déclaration du contrôleur d'émission de la réponse HTTP.
     *
     * @return void
     */
    public function registerEmitter()
    {
        $this->getContainer()->share('router.emitter', SapiEmitter::class);
    }

    /**
     * Déclaration des contrôleurs de réponse et de requête HTTP.
     *
     * @return void
     */
    public function registerPsrRequest()
    {
        $this->getContainer()->share(ServerRequestInterface::class, function () {
            return (new DiactorosFactory())->createRequest(request());
        });
    }

    /**
     * Déclaration du controleur de routage.
     *
     * @return void
     */
    public function registerRouter()
    {
        $this->getContainer()->share('router', Router::class);
    }

    /**
     * Déclaration des controleurs de strategies.
     *
     * @return void
     */
    public function registerStrategies()
    {
        $this->getContainer()->add('router.strategy.default', ApplicationStrategy::class);

        $this->getContainer()->add('router.strategy.json', JsonStrategy::class)
            ->withArgument(new ResponseFactory());
    }

    /**
     * Déclaration des controleurs d'url.
     *
     * @return void
     */
    public function registerUrl()
    {
        /*
        $this->getContainer()->share('url', function () {
            return new Url($this->getContainer()->get('router'), $this->getContainer()->get('request'));
        });

        $this->getContainer()->add('url.factory', UrlFactory::class);
        */
    }
}