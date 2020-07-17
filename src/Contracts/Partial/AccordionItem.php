<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

use tiFy\Contracts\Support\ParamsBag;

interface AccordionItem extends ParamsBag
{
    /**
     * Résolution de sortie de la classe sous forme d'une chaîne de caractères.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Initialisation.
     *
     * @return static
     */
    public function build(): AccordionItem;

    /**
     * Récupération du niveau de profondeur de l'élément.
     *
     * @return static
     */
    public function getDepth(): int;

    /**
     * Récupération du nom de qualification de l'élément.
     *
     * @return string|int
     */
    public function getId();

    /**
     * Récupération du nom de qualification du parent.
     *
     * @return string|int|null
     */
    public function getParent();

    /**
     * Vérification de l'état d'ouverture de l'élément.
     *
     * @return bool
     */
    public function isOpened(): bool;

    /**
     * Affichage de l'élément.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Définition du niveau de profondeur de l'élément.
     *
     * @param int $depth
     *
     * @return static
     */
    public function setDepth(int $depth): AccordionItem;

    /**
     * Définition de l'état d'ouverture de l'élément.
     *
     * @param bool $open
     *
     * @return static
     */
    public function setOpened(bool $open = true): AccordionItem;

    /**
     * Définition de l'instance du gestionnaire d'affichage de la liste des éléments.
     *
     * @param AccordionWalker $walker
     *
     * @return static
     */
    public function setWalker(AccordionWalker $walker): AccordionItem;
}