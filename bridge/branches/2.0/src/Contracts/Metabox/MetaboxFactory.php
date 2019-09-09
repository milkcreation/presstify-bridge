<?php

namespace tiFy\Contracts\Metabox;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Wordpress\Contracts\WpScreen;

interface MetaboxFactory extends ParamsBag
{
    /**
     * Récupération de la liste des variables passées en argument.
     *
     * @return array
     */
    public function getArgs();

    /**
     * Récupération de l'affichage du contenu.
     *
     * @return string
     */
    public function getContent();

    /**
     * Récupération de l'affichage de l'entête.
     *
     * @return string
     */
    public function getHeader();

    /**
     * Récupération du contexte d'affichage.
     *
     * @return string
     */
    public function getContext();

    /**
     * Récupération de l'indice de qualification.
     *
     * @return integer
     */
    public function getIndex();

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName();

    /**
     * Récupération du nom de qualification du parent associé.
     *
     * @return string
     */
    public function getParent();

    /**
     * Récupération de l'ordre d'affichage.
     *
     * @return integer
     */
    public function getPosition();

    /**
     * Récupération de l'instance de l'écran d'affichage.
     *
     * @return WpScreen
     */
    public function getScreen();

    /**
     * Récupération de l'intitulé de qualification.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Vérification de l'activation.
     *
     * @return boolean
     */
    public function isActive();

    /**
     * Chargement de l'écran courant Wordpress.
     *
     * @param WpScreen $screen Instance de l'écran courant.
     *
     * @return void
     */
    public function load(WpScreen $screen);
}