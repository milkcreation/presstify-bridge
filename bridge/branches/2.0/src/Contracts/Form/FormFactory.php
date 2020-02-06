<?php declare(strict_types=1);

namespace tiFy\Contracts\Form;

use tiFy\Contracts\Support\{LabelsBag, ParamsBag};

interface FormFactory extends FactoryResolver, ParamsBag
{
    /**
     * Résolution de sortie de l'affichage du formulaire.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Initialisation du contrôleur.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Récupération de la chaîne de sécurisation du formulaire (CSRF).
     *
     * @return string
     */
    public function csrf(): string;

    /**
     * Récupération de valeur(s) de champ(s) basée(s) sur leurs variables d'identifiant de qualification.
     *
     * @param mixed $tags Variables de qualification de champs.
     * string ex. "%%{{slug#1}}%% %%{{slug#2}}%%"
     * array ex ["%%{{slug#1}}%%", "%%{{slug#2}}%%"]
     * @param boolean $raw Activation de la valeur de retour au format brut.
     *
     * @return string
     */
    public function fieldTagValue($tags, $raw = true);

    /**
     * Récupération de l'action du formulaire (url).
     *
     * @return string
     */
    public function getAction(): string;

    /**
     * Récupération de la méthode de soumission du formulaire.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Récupération de l'intitulé de qualification du formulaire.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Vérification du status en erreur du formulaire.
     *
     * @return bool
     */
    public function hasError(): bool;

    /**
     * Vérification d'activation de l'agencement des éléments.
     *
     * @return boolean
     */
    public function hasGrid();

    /**
     * Récupération du numéro d'indice du formulaire.
     *
     * @return int|null
     */
    public function index();

    /**
     * Vérification d'activation automatisée.
     *
     * @return boolean
     */
    public function isAuto(): bool;

    /**
     * Vérification de préparation active.
     *
     * @return boolean
     */
    public function isPrepared(): bool;

    /**
     * Récupération d'intitulé|Définition d'intitulés|Retourne l'instance du gestionnaire d'intitulés.
     *
     * @param string|array|null $key Clé d'indexe de l'intitulé.
     * @param string $default Valeur de retour par défaut.
     *
     * @return LabelsBag|string
     */
    public function label($key = null, string $default = '');

    /**
     * Récupération du nom de qualification du formulaire.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Evénement de déclenchement à l'initialisation du formulaire en tant que formulaire courant.
     *
     * @return void
     */
    public function onSetCurrent(): void;

    /**
     * Evénement de déclenchement à la réinitialisation du formulaire courant du formulaire.
     *
     * @return void
     */
    public function onResetCurrent(): void;

    /**
     * Initialisation (préparation) du champ.
     *
     * @return static
     */
    public function prepare(): FormFactory;

    /**
     * Affichage.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Définition de l'instance.
     *
     * @param string $name Nom de qualification du formulaire.
     * @param FormManager $manager Instance du gestionnaire de formulaires.
     *
     * @return static
     */
    public function setInstance(string $name, FormManager $manager): FormFactory;

    /**
     * Définition de l'indicateur de statut de formulaire en succès.
     *
     * @param boolean $status
     *
     * @return static
     */
    public function setOnSuccess(bool $status = true): FormFactory;

    /**
     * Récupération du nom de qualification du formulaire dans les attributs de balises HTML.
     *
     * @return string
     */
    public function tagName(): string;
}