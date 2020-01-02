<?php declare(strict_types=1);

namespace tiFy\Http;

use Illuminate\Http\RedirectResponse as LaraRedirectResponse;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface as PsrResponse;
use Symfony\Bridge\PsrHttpMessage\Factory\{HttpFoundationFactory, PsrHttpFactory};
use Symfony\Component\HttpFoundation\Response as SfResponse;
use tiFy\Contracts\Http\RedirectResponse as RedirectResponseContract;
use tiFy\Contracts\Http\Response as ResponseContract;

class RedirectResponse extends LaraRedirectResponse implements RedirectResponseContract
{
    /**
     * @inheritDoc
     */
    public static function convertToPsr(?SfResponse $response = null): ?PsrResponse
    {
        if ($response = $response ?: new static('')) {
            $psr17Factory = new Psr17Factory();
            $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

            return $psrHttpFactory->createResponse($response);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @return RedirectResponseContract
     */
    public static function createFromPsr(PsrResponse $psrResponse, bool $streamed = false): SfResponse
    {
        return (new HttpFoundationFactory())->createResponse($psrResponse, $streamed);
    }

    /**
     * @inheritDoc
     */
    public static function createPsr(?string $url, int $status = 302, array $headers = []): ?PsrResponse
    {
        if ($response = new static($url, $status, $headers)) {
            return Response::convertToPsr($response);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @return RedirectResponseContract
     */
    public function instance($url = '', int $status = 200, array $headers = []): ResponseContract
    {
        return self::create($url, $status, $headers);
    }

    /**
     * @inheritDoc
     */
    public function psr(): ?PsrResponse
    {
        return self::convertToPsr($this);
    }
}