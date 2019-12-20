<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

interface Modal extends PartialFactory
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
     * Définition de l'url de traitement XHR.
     *
     * @param string|null $url
     *
     * @return $this
     */
    public function setUrl(?string $url = null): Modal;

    /**
     * Affichage d'un lien de déclenchement de la modale.
     *
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return string
     */
    public function trigger(array $attrs = []);

    /**
     * Contrôleur de traitement de la requête XHR.
     *
     * @param array ...$args Liste dynamique de variables passés en argument dans l'url de requête
     *
     * @return array
     */
    public function xhrResponse(...$args): array;
}