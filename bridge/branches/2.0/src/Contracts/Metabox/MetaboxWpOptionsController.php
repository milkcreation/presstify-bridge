<?php

namespace tiFy\Contracts\Metabox;

interface MetaboxWpOptionsController extends MetaboxController
{
    /**
     * Affichage du contenu.
     *
     * @param array $args Liste des variables passés en argument.
     * @param null $null1 Paramètre indisponible.
     * @param null $null2 Paramètre indisponible.
     *
     * @return string
     */
    public function content($args = null, $null1 = null, $null2 = null);

    /**
     * Récupération du nom de qualification de la page d'affichage.
     *
     * @return string
     */
    public function getOptionsPage();

    /**
     * Affichage du contenu.
     *
     * @param array $args Liste des variables passés en argument.
     * @param null $null1 Paramètre indisponible.
     * @param null $null2 Paramètre indisponible.
     *
     * @return string
     */
    public function header($args = null, $null1 = null, $null2 = null);

    /**
     * Listes des options à enregistrer.
     *
     * @return array
     */
    public function settings();
}