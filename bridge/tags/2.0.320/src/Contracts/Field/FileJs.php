<?php declare(strict_types=1);

namespace tiFy\Contracts\Field;

interface FileJs extends FieldDriver
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
     * Traitement des options du moteur de téléchargement Dropzone.
     * @see https://www.dropzonejs.com/#configuration
     *
     * @return $this
     */
    public function parseDropzone(): FieldDriver;

    /**
     * Définition de l'url de traitement de la requête XHR.
     *
     * @param string|null $url
     *
     * @return static
     */
    public function setUrl(?string $url = null): FieldDriver;

    /**
     * Contrôleur de traitement de la requête XHR.
     *
     * @param array ...$args Liste dynamique de variables passés en argument dans l'url de requête
     *
     * @return array
     */
    public function xhrResponse(...$args): array;
}