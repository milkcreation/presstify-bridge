<?php declare(strict_types=1);

namespace tiFy\Routing\Middleware;

use Psr\Http\Message\{ResponseInterface,ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface,RequestHandlerInterface};
use tiFy\Http\Request;
use Zend\Diactoros\Response;

class Xhr implements MiddlewareInterface
{
    /**
     * @inheritdoc
     */
    public function process(ServerRequestInterface $psrRequest, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = Request::createFromPsr($psrRequest);
        if ($request->ajax()) {
            return $handler->handle($psrRequest);
        } else {
            $phrase = __('Dans cette configuration, seules les requêtes XMLHttpRequest (XHR) sont autorisées', 'tify');

            $psrResponse = new Response();
            $psrResponse->getBody()->write(json_encode([
                'status_code'   => 500,
                'reason_phrase' => $phrase
            ]));
            $psrResponse = $psrResponse->withAddedHeader('content-type', 'application/json');

            return $psrResponse->withStatus(500, $phrase);
        }
    }
}