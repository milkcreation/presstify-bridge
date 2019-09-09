<?php

namespace tiFy\Contracts\Partial;

use tiFy\Contracts\View\ViewController;
use tiFy\Contracts\Kernel\QueryCollection;

interface AccordionItems extends QueryCollection
{
    /**
     * Résolution de sortie de l'affichage du contrôleur.
     *
     * @return string
     */
    public function __toString();

    /**
     * Itération d'affichage la liste des éléments.
     *
     * @param AccordionItem[] $items Liste des éléments.
     * @param int $depth Niveau de profondeur courant de l'itération.
     * @param mixed $parent Parent courant de l'itération.
     *
     * @return string
     */
    public function walk($items = [], $depth = 0, $parent = null);

    /**
     * Récupération du rendu d'affichage.
     *
     * @return ViewController
     */
    public function render();

    /**
     * Définition du controleur d'affichage associé.
     *
     * @param Accordion $partial Contrôleur d'affichage associé.
     *
     * @return static
     */
    public function setPartial(Accordion $partial);

    /**
     * Définition de la liste des éléments ouverts à l'initialisation.
     *
     * @param mixed $opened Liste des éléments ouverts à l'initialisation.
     *
     * @return static
     */
    public function setOpened($opened = null);
}