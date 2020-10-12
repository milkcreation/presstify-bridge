<?php declare(strict_types=1);

namespace tiFy\Routing\Middleware;

use Laminas\Diactoros\Response;
use tiFy\Routing\BaseMiddleware;
use Psr\Http\{
    Message\ResponseInterface as PsrResponse,
    Message\ServerRequestInterface as PsrRequest,
    Server\RequestHandlerInterface as RequestHandler
};

class XhrMiddleware extends BaseMiddleware
{
    /**
     * @inheritDoc
     */
    public function process(PsrRequest $psrRequest, RequestHandler $handler): PsrResponse
    {
        if ('XMLHttpRequest' == $psrRequest->getHeaderLine('X-Requested-With')) {
            return $handler->handle($psrRequest);
        } else {
            $phrase = __('Dans cette configuration, seules les requêtes XMLHttpRequest (XHR) sont autorisées', 'tify');

            $psrResponse = new Response();
            $psrResponse->getBody()->write(json_encode([
                'status_code' => 500,
                'reason_phrase' => $phrase,
            ]));
            $psrResponse = $psrResponse->withAddedHeader('content-type', 'application/json');

            return $psrResponse->withStatus(500, $phrase);
        }
    }
}