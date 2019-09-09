<?php

namespace tiFy\Contracts\Form;

use tiFy\Contracts\View\ViewEngine;

interface FactoryResolver
{
    /**
     * Récupération de l'instance du contrôleur d'un addon associé au formulaire.
     *
     * @param string $name Nom de qualification de l'addon.
     *
     * @return AddonController
     */
    public function addon($name);

    /**
     * Récupération de l'instance du contrôleur des addons associés au formulaire.
     *
     * @return FactoryAddons|AddonController[]
     */
    public function addons();

    /**
     * Récupération de l'instance du contrôleur des boutons associés au formulaire.
     *
     * @return FactoryButtons|ButtonController[]
     */
    public function buttons();

    /**
     * Récupération de l'instance du contrôleur des événements associés au formulaire ou déclenchement d'un événement.
     *
     * @param string $name Nom de qualification de l'événement.
     *
     * @return mixed|FactoryEvents
     */
    public function events($name = null);

    /**
     * Récupération de l'instance du contrôleur d'un champ associé au formulaire.
     *
     * @param null|string $slug Identifiant de qualification du champ.
     *
     * @return FactoryField
     */
    public function field($slug = null);

    /**
     * Récupération de l'instance du contrôleur des champs associés au formulaire.
     *
     * @return FactoryFields|FactoryField[]
     */
    public function fields();

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
     * Récupération de l'instance du contrôleur de formulaire associé.
     *
     * @return FormFactory
     */
    public function form();

    /**
     * Récupération de l'instance du contrôleur des messages de notification associés au formulaire.
     *
     * @return FactoryNotices
     */
    public function notices();

    /**
     * Récupération d'une option ou de la liste complète des options du formulaire.
     *
     * @param null|string $key Clé d'indice de l'option.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function option($key = null, $default = null);

    /**
     * Récupération de l'instance du contrôleur des options associées au formulaire.
     *
     * @return FactoryOptions
     */
    public function options();

    /**
     * Récupération de l'instance du contrôleur de traitement de la requête de soumission associée au formulaire.
     *
     * @return FactoryRequest
     */
    public function request();

    /**
     * Résolution de service fournis par le contructeur de formulaire.
     *
     * @param string $alias Nom de qualification du service.
     * @param array $args Liste des variables passées en argument au service.
     *
     * @return mixed
     */
    public function resolve($alias, $args = []);

    /**
     * Récupération de l'instance du contrôleur de session associé au formulaire.
     *
     * @return FactorySession
     */
    public function session();

    /**
     * Récupération de l'instance du contrôleur de validation associé au formulaire.
     *
     * @return FactoryValidation
     */
    public function validation();

    /**
     * Récupération d'un instance du controleur de liste des gabarits d'affichage ou d'un gabarit d'affichage.
     * {@internal
     *  - cas 1 : Aucun argument n'est passé à la méthode, retourne l'instance du controleur de gabarit d'affichage.
     *  - cas 2 : Rétourne le gabarit d'affichage en passant les variables en argument.
     * }
     *
     * @param null|string $view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return FactoryView|ViewEngine
     */
    public function viewer($view = null, $data = []);
}