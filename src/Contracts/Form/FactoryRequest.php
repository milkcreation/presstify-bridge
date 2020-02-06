<?php declare(strict_types=1);

namespace tiFy\Contracts\Form;

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
     * Traitement de la requête de soumission du formulaire.
     *
     * @return void
     */
    public function handle(): void;

    /**
     * Préparation des données de traitement de la requête.
     *
     * @return FactoryRequest
     */
    public function prepare(): FactoryRequest;

    /**
     * Traitement de la validation de soumission du formulaire.
     *
     * @return FactoryRequest
     */
    public function validate(): FactoryRequest;

    /**
     * Réinitialisation des champs.
     *
     * @return FactoryRequest
     */
    public function resetFields(): FactoryRequest;

    /**
     * Vérification de l'origine de la requête.
     *
     * @return boolean
     */
    public function verify(): bool;
}