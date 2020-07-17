<?php declare(strict_types=1);

namespace tiFy\Contracts\Field;

interface CheckboxWalker
{
    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Initialisation
     *
     * @return static
     */
    public function build(): CheckboxWalker;

    /**
     * Vérification d'existance d'éléments.
     *
     * @return bool
     */
    public function exists(): bool;

    /**
     * Déclaration des éléments sélectionnés.
     *
     * @return static
     */
    public function registerChecked(): CheckboxWalker;

    /**
     * Récupération du rendu d'affichage de l'élément.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Définition du controleur de champ associé.
     *
     * @param CheckboxCollection $field
     *
     * @return static
     */
    public function setField(CheckboxCollection $field): CheckboxWalker;

    /**
     * Définition d'un élément.
     *
     * @param CheckboxChoice|array $item
     * @param string|int $key
     *
     * @return CheckboxChoice
     */
    public function setItem($item, $key = null): CheckboxChoice;
}