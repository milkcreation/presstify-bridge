<?php declare(strict_types=1);

namespace tiFy\Contracts\Http;

use Psr\Http\Message\UriInterface;

interface Uri extends UriInterface
{
    /**
     * Creation d'une nouvelle instance basée sur la requête.
     *
     * @param Request $request Requête HTTP.
     *
     * @return static
     */
    public static function createFromRequest(Request $request): Uri;

    /**
     * Récupération de la requête HTTP associée.
     *
     * @param Request $request
     *
     * @return Request
     */
    public function request(): ?Request;

    /**
     * Récupération du schéma et l'hôte.
     *
     * @return string
     */
    public function getSchemeAndHttpHost(): string;

    /**
     * Récupération du chemin relatif d'une url du domaine.
     *
     * @param string $url
     *
     * @return string|null
     */
    public function getRelativeUriFromUrl(string $url, $base = true): ?string;

    /**
     * Définition de la requête HTTP associée.
     *
     * @param Request $request
     *
     * @return $this
     */
    public function setRequest(Request $request): Uri;
}