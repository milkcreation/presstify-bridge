<?php

namespace tiFy\Contracts\Form;

use tiFy\Contracts\Support\ParamsBag;

interface FactoryField extends FactoryResolver, ParamsBag
{
    /**
     * Résolution de sortie de l'affichage.
     *
     * @return string
     */
    public function __toString();

    /**
     * Récupération du pré-affichage du champ.
     *
     * @return string
     */
    public function after(): string;

    /**
     * Récupération du post-affichage du champ.
     *
     * @return string
     */
    public function before(): string;

    /**
     * Récupération d'attributs d'addon.
     * {@internal Retourne la liste complète si $key est à null.}
     *
     * @param string $name Nom de qualification de l'addon.
     * @param null|string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getAddonOption($name, $key = null, $default = null);

    /**
     * Récupération de l'instance du contrôleur de champ.
     *
     * @return FieldController
     */
    public function getController();

    /**
     * Récupération d'un ou de la liste des attributs de configuration complémentaires.
     *
     * @param null|string $key Clé d'indexe de l'attribut. Syntaxe à point permise. Laisser à null (défaut) pour
     *                         récupérer la liste complète.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getExtras($key = null, $default = null);

    /**
     * Récupération du groupe d'appartenance.
     *
     * @return FactoryGroup
     */
    public function getGroup();

    /**
     * Récupération de l'indice de qualification de la variable de requête.
     *
     * @return string
     */
    public function getName();

    /**
     * Récupération de l'ordre d'affichage.
     *
     * @return int
     */
    public function getPosition();

    /**
     * Récupération d'attribut de champ requis.
     *
     * @param null|string $key Clé d'indexe d'attributs. Syntaxe à point permise.
     *                         Retour la liste complète si null (défaut).
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getRequired($key = null, $default = null);

    /**
     * Récupération de l'identifiant de qualification.
     *
     * @return string
     */
    public function getSlug();

    /**
     * Récupération de l'intitulé de qualification.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Récupération du type.
     *
     * @return string
     */
    public function getType();

    /**
     * Récupération de la valeur.
     *
     * @param boolean Activation de la valeur de retour au format brut.
     *
     * @return mixed
     */
    public function getValue($raw = true);

    /**
     * Récupération de la liste des valeurs.
     *
     * @param bool $raw Activation de la valeur de retour au format brut.
     * @param null|string $glue Caractère d'assemblage de la valeur.
     *
     * @return string|array
     */
    public function getValues($raw = true, $glue = ', ');

    /**
     * Vérification d'existance d'une étiquette.
     *
     * @return boolean
     */
    public function hasLabel();

    /**
     * Vérification d'existance d'encapsuleur HTML.
     *
     * @return boolean
     */
    public function hasWrapper();

    /**
     * Vérification si le champ retourne des erreurs de traitement.
     *
     * @return boolean
     */
    public function onError();

    /**
     * Traitement récursif des tests de validation.
     *
     * @param mixed $validations Test de validation à traiter.
     * @param array $results Liste des tests de validations traités.
     *
     * @return array
     */
    public function parseValidations($validations, $results = []);

    /**
     * Initialisation (préparation) du champ.
     *
     * @return void
     */
    public function prepare();

    /**
     * Réinitionalisation  de la valeur.
     *
     * @return self
     */
    public function resetValue();

    /**
     * Rendu de l'affichage.
     *
     * @return string
     */
    public function render();

    /**
     * Préparation du rendu de l'affichage.
     *
     * @return void
     */
    public function renderPrepare();

    /**
     * Définition d'une attributs de configuration complémentaire.
     *
     * @param string $key Clé d'indexe de l'attribut.
     * @param mixed $value Valeur à définir.
     *
     * @return array
     */
    public function setExtra($key, $value);

    /**
     * Définition du statut de l'indicateur de champ en erreur.
     *
     * @param boolean $status
     *
     * @return static
     */
    public function setOnError(bool $status = true): FactoryField;

    /**
     * Définition de l'ordre d'affichage.
     *
     * @param int $position Valeur de la position.
     *
     * @return $this
     */
    public function setPosition($position = 0);

    /**
     * Définition de la valeur d'un champ.
     *
     * @param mixed $value Valeur à définir.
     *
     * @return $this
     */
    public function setValue($value);

    /**
     * Vérification d'une propriété ou récupération de la liste des proriétés de support .
     *
     * @param null|string $support Propriété du support à vérifier.
     *
     * @return array|boolean
     */
    public function supports($support = null);
}