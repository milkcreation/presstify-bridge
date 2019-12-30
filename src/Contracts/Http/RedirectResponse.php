<?php declare(strict_types=1);

namespace tiFy\Contracts\Http;

use Illuminate\Http\RedirectResponse as LaraRedirectResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * @mixin LaraRedirectResponse
 */
interface RedirectResponse
{
    /**
     * Création d'une instance de reponse de redirection PSR.
     *
     * @param string|null $url Url de redirection.
     * @param int $status Code du statut de redirection.
     * @param array $headers Liste des entêtes complémentaires.
     *
     * @return ResponseInterface|null
     */
    public static function createPsr(?string $url, int $status = 302, array $headers = []): ?ResponseInterface;
}