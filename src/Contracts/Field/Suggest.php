<?php declare(strict_types=1);

namespace tiFy\Contracts\Field;

interface Suggest extends FieldFactory
{
    /**
     * Récupération de l'url de traitement ajax de récupération des éléments associés.
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Définition de l'url de traitement ajax de récupération des éléments associés.
     *
     * @param string|null $url
     *
     * @return static
     */
    public function setUrl(?string $url = null): FieldFactory;

    /**
     * Traitement de la réponse Xhr de récupération des éléments associés.
     *
     * @param array ...$args Liste dynamique de variables passés en argument dans l'url de requête
     *
     * @return array
     */
    public function xhrResponse(...$args): array;
}