<?php declare(strict_types=1);

namespace tiFy\Contracts\Form;

use tiFy\Contracts\Support\ParamsBag;

interface FactoryRequest extends FactoryResolver, ParamsBag
{
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
     * Redirection à l'issue de la soumission du formulaire.
     *
     * @return void
     */
    public function redirect(): void;

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