<?php

namespace tiFy\Contracts\Field;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Field\Driver\{Label\Label, Checkbox\Checkbox};

interface CheckboxChoice extends ParamsBag
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString();

    /**
     * Récupération de l'intance du champ checkbox.
     *
     * @return Checkbox
     */
    public function getCheckbox();

    /**
     * Récupération du nom de soumission de la requête de traitement.
     *
     * @return string
     */
    public function getName();

    /**
     * Récupération de l'intance du champ label.
     *
     * @return Label
     */
    public function getLabel();

    /**
     * Récupération de la valeur de soumission de la requête de traitement.
     *
     * @return mixed|null
     */
    public function getValue();

    /**
     * Vérification de l'indicateur de selection de l'élément.
     *
     * @return mixed
     */
    public function isChecked();

    /**
     * Définition du nom de soumission de la requête de traitement.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name);

    /**
     * Définition de la selection de l'élément pour la requête de traitement.
     *
     * @return self
     */
    public function setChecked();

    /**
     * Récupération du rendu d'affichage de l'élément.
     *
     * @return string
     */
    public function render();
}