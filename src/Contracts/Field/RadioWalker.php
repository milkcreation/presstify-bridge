<?php declare(strict_types=1);

namespace tiFy\Contracts\Field;

interface RadioWalker
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
    public function build(): RadioWalker;

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
    public function registerChecked(): RadioWalker;

    /**
     * Récupération du rendu d'affichage de l'élément.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Définition du controleur de champ associé.
     *
     * @param RadioCollection $field
     *
     * @return static
     */
    public function setField(RadioCollection $field): RadioWalker;

    /**
     * Définition d'un élément.
     *
     * @param RadioChoice|array $item
     * @param string|int $key
     *
     * @return RadioChoice
     */
    public function setItem($item, $key = null): RadioChoice;
}