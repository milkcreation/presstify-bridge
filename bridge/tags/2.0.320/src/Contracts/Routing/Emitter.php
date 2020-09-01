<?php declare(strict_types=1);

namespace tiFy\Contracts\Routing;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use League\Route\Middleware\MiddlewareAwareInterface;
use Psr\Http\Message\ResponseInterface as PsrResponse;

interface Emitter extends MiddlewareAwareInterface, EmitterInterface
{
    /**
     * Traitement de la réponse HTTP.
     *
     * @param PsrResponse $psrResponse
     *
     * @return PsrResponse
     */
    public function handle(PsrResponse $psrResponse) : PsrResponse;

    /**
     * Expédition de la réponse HTTP au client.
     *
     * @param PsrResponse $psrResponse
     *
     * @return PsrResponse
     */
    public function send(PsrResponse $psrResponse): PsrResponse;

    /**
     * Définition du gestionnaire de routage.
     *
     * @param Router $router
     *
     * @return static
     */
    public function setRouter(Router $router): Emitter;
}
