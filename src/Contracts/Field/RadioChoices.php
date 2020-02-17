<?php

namespace tiFy\Contracts\Field;

use tiFy\Contracts\Support\Collection;

interface RadioChoices extends Collection
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString();

    /**
     * Récupération du rendu d'affichage de l'élément.
     *
     * @return string
     */
    public function render();

    /**
     * Définition de la selection de l'élément pour la requête de traitement.
     *
     * @return self
     */
    public function setChecked($checked = null);

    /**
     * Définition du controleur de champ associé.
     *
     * @param RadioCollection $field
     *
     * @return static
     */
    public function setField(RadioCollection $field);
}