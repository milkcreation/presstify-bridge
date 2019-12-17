<?php declare(strict_types=1);

namespace tiFy\Contracts\Http;

use Illuminate\Http\Request as LaraRequest;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface Request
 * @package tiFy\Contracts\Http
 *
 * @mixin LaraRequest
 */
interface Request
{
    /**
     * Convertion d'une instance de requête en requête HTTP Psr-7
     *
     * @param Request $request
     *
     * @return ServerRequestInterface|null
     */
    public static function convertToPsr(?Request $request = null): ?ServerRequestInterface;

    /**
     * Création d'une instance depuis une requête PSR-7.
     *
     * @param ServerRequestInterface $psrRequest
     *
     * @return static
     */
    public static function createFromPsr(ServerRequestInterface $psrRequest): Request;

    /**
     * Définition de l'instance globale basée sur les variables globales de la requête courante.
     *
     * @return Request
     */
    public static function setFromGlobals(): Request;
}