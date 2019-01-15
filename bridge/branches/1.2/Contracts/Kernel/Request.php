<?php

namespace tiFy\Contracts\Kernel;

use Illuminate\Http\Request as IlluminateHttpRequest;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\ServerBag;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface Request
 * @package tiFy\Kernel\Http
 *
 * @mixin IlluminateHttpRequest
 */
interface Request
{
    /**
     * Récupération de la classe de rappel de propriété de la requête globale.
     * @see https://laravel.com/api/5.6/Illuminate/Http/Request.html
     * @see https://symfony.com/doc/current/components/http_foundation.html
     * @see http://api.symfony.com/4.0/Symfony/Component/HttpFoundation/ParameterBag.html
     *
     * @param string $property Propriété de la requête à traiter.
     * $_POST (alias post, request)|$_GET (alias get, query)|$_COOKIE (alias cookie, cookies)|attributes
     * |$_FILES (alias files)|SERVER (alias server)|headers.
     *
     * @return Request|FileBag|HeaderBag|ParameterBag|ServerBag
     */
    public function getProperty($property = '');

    /**
     * Création d'une instance depuis une requête PSR-7.
     *
     * @param ServerRequestInterface $psrRequest Requête PSR
     *
     * @return static|IlluminateHttpRequest
     */
    public function createFromPsr(ServerRequestInterface $psrRequest);
}