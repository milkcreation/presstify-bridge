<?php declare(strict_types=1);

namespace tiFy\Http;

use Illuminate\Http\Response as BaseResponse;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface as PsrResponse;
use Symfony\Component\HttpFoundation\Response as SfResponse;
use Symfony\Bridge\PsrHttpMessage\Factory\{HttpFoundationFactory, PsrHttpFactory};
use tiFy\Contracts\Http\Response as ResponseContract;

class Response extends BaseResponse implements ResponseContract
{
    /**
     * @inheritDoc
     */
    public static function convertToPsr(?SfResponse $response = null): ?PsrResponse
    {
        if ($response = $response ?: new static()) {
            $psr17Factory = new Psr17Factory();
            $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

            return $psrHttpFactory->createResponse($response);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @return ResponseContract
     */
    public static function createFromPsr(PsrResponse $psrResponse, bool $streamed = false): SfResponse
    {
        return (new HttpFoundationFactory())->createResponse($psrResponse, $streamed);
    }

    /**
     * @inheritDoc
     */
    public function instance($content = '', int $status = 200, array $headers = []): ResponseContract
    {
        return self::create($content, $status, $headers);
    }

    /**
     * @inheritDoc
     */
    public function psr(): ?PsrResponse
    {
        return self::convertToPsr($this);
    }
}