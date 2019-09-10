<?php declare(strict_types=1);

namespace tiFy\Contracts\Field;

interface FileJs extends FieldFactory
{
    /**
     * Récupération de l'url de traitement ajax.
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Traitement des options du moteur de téléchargement Dropzone.
     * @see https://www.dropzonejs.com/#configuration
     *
     * @return $this
     */
    public function parseDropzone(): FieldFactory;

    /**
     * Définition de l'url de traitement ajax.
     *
     * @param string|null $url
     *
     * @return static
     */
    public function setUrl(?string $url = null): FieldFactory;

    /**
     * Génération de la réponse HTTP via une requête XHR.
     *
     * @return array
     */
    public function xhrResponse(): array;
}