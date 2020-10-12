<?php declare(strict_types=1);

namespace tiFy\Contracts\Form;

use tiFy\Http\RedirectResponse;
use tiFy\Contracts\Support\ParamsBag;

interface FactoryRequest extends FactoryResolver, ParamsBag
{
    /**
     * Récupération de l'url de redirection.
     *
     * @return string
     */
    public function getRedirectUrl(): string;

    /**
     * Récupération de la valeur de la protection CSRF.
     *
     * @return string
     */
    public function getToken(): string;

    /**
     * Traitement de la requête de soumission du formulaire.
     *
     * @return RedirectResponse|null
     */
    public function handle(): ?RedirectResponse;

    /**
     * Préparation des données de traitement de la requête.
     *
     * @return static
     */
    public function prepare(): FactoryRequest;

    /**
     * Redirection de la requête de traitement du formulaire.
     *
     * @return RedirectResponse
     */
    public function redirect(): RedirectResponse;

    /**
     * Réinitialisation de la requête.
     *
     * @return static
     */
    public function reset(): FactoryRequest;

    /**
     * Définition de l'url de redirection.
     *
     * @param string $url
     * @param bool $raw Désactivation du formatage (indicateur de succès && ancre).
     *
     * @return static
     */
    public function setRedirectUrl(string $url, bool $raw = false): FactoryRequest;

    /**
     * Traitement de la validation de soumission du formulaire.
     *
     * @return static
     */
    public function validate(): FactoryRequest;

    /**
     * Vérification de l'origine de la requête.
     *
     * @return boolean
     */
    public function verify(): bool;
}