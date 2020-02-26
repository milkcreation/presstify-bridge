<?php declare(strict_types=1);

namespace tiFy\Contracts\Field;

interface Suggest extends FieldDriver
{
    /**
     * Récupération de l'url de traitement de la requête XHR.
     *
     * @param array ...$params Liste des paramètres optionnels de formatage de l'url.
     *
     * @return string
     */
    public function getUrl(...$params): string;

    /**
     * Définition de l'url de traitement de la requête XHR.
     *
     * @param string|null $url
     *
     * @return static
     */
    public function setUrl(?string $url =  null): FieldDriver;

    /**
     * Contrôleur de traitement de la requête XHR.
     *
     * @param array ...$args Liste dynamique de variables passés en argument dans l'url de requête
     *
     * @return array
     */
    public function xhrResponse(...$args): array;
}