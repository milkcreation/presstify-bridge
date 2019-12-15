<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

use tiFy\Contracts\Support\ParamsBag;

interface TabItem extends ParamsBag
{
    /**
     * Résolution de sortie la classe sous forme de chaîne de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Récupération de l'identifiant de qualification.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Récupération de l'identifiant d'indexation'.
     *
     * @return int
     */
    public function getIndex(): int;

    /**
     * Récupération de la liste des éléments enfants.
     *
     * @return TabItem[]|array
     */
    public function getChilds(): iterable;

    /**
     * Récupération du contenu d'affichage de l'élément.
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Liste des attributs HTML du contenu de l'élément.
     *
     * @param boolean $linearized Activation de la linéarisation de la liste des attributs HTML.
     *
     * @return string|array
     */
    public function getContentAttrs($linearized = true);

    /**
     * Récupération du niveau de profondeur d'affichage de l'élément.
     *
     * @return string
     */
    public function getDepth();

    /**
     * Récupération du nom de qualification de l'élément.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Liste des attributs HTML du lien de navigation de l'élément.
     *
     * @param boolean $linearized Activation de la linéarisation de la liste des attributs HTML.
     *
     * @return string|array
     */
    public function getNavAttrs($linearized = true);

    /**
     * Récupération du nom de qualification de l'élément parent.
     *
     * @return TabItem
     */
    public function getParent(): ?TabItem;

    /**
     * Récupération de l'intitulé de qualification.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Préparation de l'élément.
     *
     * @param TabItems $manager
     *
     * @return static
     */
    public function prepare(TabItems $manager): TabItem;

    /**
     * Définition de l'activation de l'élément.
     *
     * @param string|null $active Nom de qualification de l'élément actif.
     *
     * @return static
     */
    public function setActivation(?string $active): TabItem;

    /**
     * Définition du niveau de profondeur dans l'interface d'affichage.
     *
     * @param int $depth
     *
     * @return static
     */
    public function setDepth(int $depth = 0): TabItem;
}