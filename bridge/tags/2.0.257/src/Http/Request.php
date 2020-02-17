<?php declare(strict_types=1);

namespace tiFy\Http;

use Illuminate\Http\Request as LaraRequest;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use tiFy\Contracts\Http\Request as RequestContract;

class Request extends LaraRequest implements RequestContract
{
    /**
     * Instance basée sur les variable globales de la requête courante.
     * @var RequestContract
     */
    protected static $global;

    /**
     * @inheritdoc
     */
    public static function createFromPsr(ServerRequestInterface $psrRequest): RequestContract
    {
        $request = (new HttpFoundationFactory())->createRequest($psrRequest);

        return self::createFromBase($request);
    }

    /**
     * @inheritdoc
     */
    public static function convertToPsr(?RequestContract $request = null): ?ServerRequestInterface
    {
        if ($request = $request ?: self::setFromGlobals()) {
            $psr17Factory = new Psr17Factory();
            $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

            return $psrHttpFactory->createRequest($request);
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public static function setFromGlobals(): RequestContract
    {
        return self::$global = self::$global ?? self::capture();
    }
}