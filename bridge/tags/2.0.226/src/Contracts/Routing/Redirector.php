<?php declare(strict_types=1);

namespace tiFy\Contracts\Routing;

use Psr\Http\Message\ResponseInterface as Response;

interface Redirector
{
    /**
     * Création d'une instance de reponse de redirection PSR.
     * @see https://fr.wikipedia.org/wiki/Liste_des_codes_HTTP
     *
     * @param string|null $path Url de redirection.
     * @param int $status Code du statut de redirection.
     * @param array $headers Liste des entêtes complémentaires.
     *
     * @return Response|null
     */
    public function to(string $path, int $status = 302, array $headers = []): ?Response;

    /**
     * Création d'une instance de reponse de redirection PSR basé sur le nom de qualification d'une route nommée.
     *
     * @param string $name Nom de qualification de la route.
     * @param array $parameters Liste des paramètres passés en argument dans l'url de la route.
     * @param int $status Code du statut de redirection.
     * @param array $headers Liste des entêtes complémentaires.
     *
     * @return Response|null
     */
    public function route(string $name, array $parameters = [], int $status = 302, array $headers = []): ?Response;
}