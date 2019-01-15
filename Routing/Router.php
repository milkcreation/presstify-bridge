<?php declare(strict_types=1);

namespace tiFy\Routing;

use League\Route\Router as LeagueRouter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\HttpHandlerRunner\Emitter\EmitterInterface;

class Router extends LeagueRouter
{
    /**
     * @inheritdoc
     */
    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        if (is_null($this->getStrategy())) :
            $this->setStrategy(app()->get('router.strategy.default'));
        endif;

        return parent::dispatch($request);
    }

    /**
     * @inheritdoc
     */
    public function emit(ResponseInterface $response) : void
    {
        /** @var EmitterInterface $emitter */
        $emitter = app()->get('router.emitter');

        $emitter->emit($response);
    }
}