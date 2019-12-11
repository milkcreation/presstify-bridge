<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

use tiFy\Contracts\Support\Collection;

interface AccordionItems extends Collection
{
    /**
     * Résolution de sortie de l'affichage du contrôleur.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Itération d'affichage la liste des éléments.
     *
     * @param AccordionItem[] $items Liste des éléments.
     * @param int $depth Niveau de profondeur courant de l'itération.
     * @param mixed $parent Parent courant de l'itération.
     *
     * @return string
     */
    public function walker($items = [], $depth = 0, $parent = null): string;

    /**
     * Récupération du rendu d'affichage.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Définition du controleur d'affichage associé.
     *
     * @param Accordion $partial Contrôleur d'affichage associé.
     *
     * @return static
     */
    public function setPartial(Accordion $partial): AccordionItems;

    /**
     * Définition de la liste des éléments ouverts à l'initialisation.
     *
     * @param mixed $opened Liste des éléments ouverts à l'initialisation.
     *
     * @return static
     */
    public function setOpened($opened = null): AccordionItems;
}