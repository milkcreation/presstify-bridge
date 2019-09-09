<?php

namespace tiFy\Contracts\Partial;

use tiFy\Contracts\Kernel\ParamsBag;

interface AccordionItem extends ParamsBag
{
    /**
     * Récupération du contenu à afficher.
     *
     * @return string
     */
    public function getContent();

    /**
     * Récupération du nom de qualification de l'élément.
     *
     * @return string
     */
    public function getName();

    /**
     * Récupération du nom de qualification du parent.
     *
     * @return string
     */
    public function getParent();

    /**
     * Vérification d'ouverture de l'élément.
     *
     * @return boolean
     */
    public function isOpen();

    /**
     * Définition du niveau de profondeur de l'élément.
     *
     * @param int $depth Niveau de profondeur.
     *
     * @return static
     */
    public function setDepth($depth);
}