<?php declare(strict_types=1);

namespace tiFy\Contracts\Routing;

use Psr\Http\Message\ResponseInterface as PsrResponse;
use Psr\Http\Server\MiddlewareInterface;

interface Middleware extends MiddlewareInterface
{
    /**
     * Initialisation.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Pré-traitement de la réponse HTTP avant son expédition au client.
     *
     * @param PsrResponse $psrResponse
     * @param Emitter $emitter
     *
     * @return PsrResponse
     */
    public function emit(PsrResponse $psrResponse, Emitter $emitter): PsrResponse;
}
