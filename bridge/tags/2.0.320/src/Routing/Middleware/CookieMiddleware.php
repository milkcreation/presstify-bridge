<?php declare(strict_types=1);

namespace tiFy\Routing\Middleware;

use Psr\Http\Message\ResponseInterface as PsrResponse;
use tiFy\Contracts\Routing\Emitter;
use tiFy\Routing\BaseMiddleware;
use tiFy\Cookie\Cookie;

class CookieMiddleware extends BaseMiddleware
{
    /**
     * @inheritDoc
     */
    public function emit(PsrResponse $psrResponse, Emitter $emitter): PsrResponse
    {
        if (!headers_sent() && ($cookies = Cookie::fetchQueued())) {
            foreach ($cookies as $cookie) {
                $psrResponse = $psrResponse->withAddedHeader('Set-Cookie', (string)$cookie);
            }
        }

        return $psrResponse;
    }
}