<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Field;

use tiFy\Contracts\Field\Suggest as BaseSuggest;

interface Suggest extends BaseSuggest
{
    /**
     * Traitement de la réponse Xhr de récupération des posts Wordpress associés.
     *
     * @param array ...$args Liste dynamique de variables passés en argument dans l'url de requête.
     *
     * @return array
     */
    public function xhrResponsePostQuery(...$args): array;

    /**
     * Traitement de la réponse Xhr de récupération des termes de taxonomied Wordpress associés.
     *
     * @param array ...$args Liste dynamique de variables passés en argument dans l'url de requête.
     *
     * @return array
     */
    public function xhrResponseTermQuery(...$args): array;

    /**
     * Traitement de la réponse Xhr de récupération des utilisateurs Wordpress associés.
     *
     * @param array ...$args Liste dynamique de variables passés en argument dans l'url de requête.
     *
     * @return array
     */
    public function xhrResponseUserQuery(...$args): array;
}