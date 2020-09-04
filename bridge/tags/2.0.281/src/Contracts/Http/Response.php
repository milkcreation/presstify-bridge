<?php declare(strict_types=1);

namespace tiFy\Contracts\Http;

use Illuminate\Http\Response as LaraResponse;
use Symfony\Component\HttpFoundation\Response as SfResponse;
use Psr\Http\Message\ResponseInterface;
use tiFy\Contracts\Http\Response as ResponseContract;

/**
 * @mixin LaraResponse
 */
interface Response
{
    /**
     * Convertion d'une instance de réponse en réponse HTTP PSR-7.
     *
     * @param SfResponse $response
     *
     * @return ResponseInterface|null
     */
    public static function convertToPsr(?SfResponse $response = null): ?ResponseInterface;

    /**
     * Création d'une instance depuis une réponse PSR-7.
     *
     * @param ResponseInterface $psrResponse
     * @param boolean $streamed
     *
     * @return static
     */
    public static function createFromPsr(ResponseInterface $psrResponse, bool $streamed): SfResponse;

    /**
     * Récupération d'une instance de la réponse.
     *
     * @param mixed $content Contenu de la reponse HTTP.
     * @param int $status Statut de la réponse.
     * @param array $headers Liste des entête passées à la réponse HTTP.
     *
     * @return static
     */
    public function instance($content = '', int $status = 200, array $headers = []): ResponseContract;

    /**
     * Convertion au format PSR-7.
     *
     * @return ResponseInterface|null
     */
    public function psr(): ?ResponseInterface;
}