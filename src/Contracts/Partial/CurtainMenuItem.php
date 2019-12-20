<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

use tiFy\Contracts\Support\ParamsBag;

interface CurtainMenuItem extends ParamsBag
{
    /**
     * Vérification d'existance d'éléments enfants associés.
     *
     * @return boolean
     */
    public function hasChild(): bool;

    /**
     * Vérification d'existance d'un élément parent associé.
     *
     * @return boolean
     */
    public function hasParent(): bool;

    /**
     * Récupération de la liste des attributs HTML.
     *
     * @param boolean $linearized Linéarisation de la liste des attributs HTML.
     *
     * @return string|array
     */
    public function getAttrs(bool $linearized = true);

    /**
     * Récupération de la liste des attributs du bouton de retour.
     *
     * @return array
     */
    public function getBack(): array;

    /**
     * Récupération de la liste des éléments associés (enfants).
     *
     * @return CurtainMenuItem[]|null
     */
    public function getChilds(): ?array;

    /**
     * Récupération du niveau de profondeur de l'élément.
     *
     * @return int
     */
    public function getDepth(): int;

    /**
     * Récupération du nom de qualification de l'élément.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération de la liste des attributs de l'interface de navigation vers le panneau de l'élément.
     *
     * @return array
     */
    public function getNav(): array;

    /**
     * Récupération de l'élément parent.
     *
     * @return CurtainMenuItem|null
     */
    public function getParent(): ?CurtainMenuItem;

    /**
     * Récupération du nom de qualification de l'élément parent.
     *
     * @return string
     */
    public function getParentName(): ?string;

    /**
     * Récupération de la liste des attributs de l'intitulé de qualification.
     *
     * @return array
     */
    public function getTitle(): array;

    /**
     * Vérification de sélection de l'élément.
     *
     * @return boolean
     */
    public function isSelected(): bool;

    /**
     * Définition du niveau de profondeur de l'élément.
     *
     * @param integer $depth
     *
     * @return static
     */
    public function setDepth(int $depth): CurtainMenuItem;

    /**
     * Définition de l'instance du gestionnaire d'éléments associé.
     *
     * @param CurtainMenuItems $manager
     *
     * @return static
     */
    public function setManager(CurtainMenuItems $manager): CurtainMenuItem;

    /**
     * Définition de la sélection de l'élément.
     *
     * @param boolean $selected
     *
     * @return static
     */
    public function setSelected($selected = false): CurtainMenuItem;

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function parse(): CurtainMenuItem;
}