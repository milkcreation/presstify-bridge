<?php declare(strict_types=1);

namespace tiFy\Http;

use Illuminate\Http\RedirectResponse as LaraRedirectResponse;
use Psr\Http\Message\ResponseInterface;
use tiFy\Contracts\Http\RedirectResponse as RedirectResponseContract;

class RedirectResponse extends LaraRedirectResponse implements RedirectResponseContract
{
    /**
     * @inheritDoc
     */
    public static function createPsr(?string $url, int $status = 302, array $headers = []): ?ResponseInterface
    {
        if ($response = new static($url, $status, $headers)) {
            return Response::convertToPsr($response);
        }

        return null;
    }
}