<?php

namespace tiFy\Wordpress\Contracts;

use WP_Screen;

interface WpScreen
{
    /**
     * Récupération de l'instance WP_Screen associée.
     *
     * @param null|string|WP_Screen
     *
     * @return null|WP_Screen
     */
    public static function get($screen);

    /**
     * Récupération de l'identifiant de qualification de l'accroche de l'écran Wordpress.
     *
     * @return string
     */
    public function getHookname();

    /**
     * Récupération du nom de qualification de l'objet Wordpress en relation.
     *
     * @return string
     */
    public function getObjectName();

    /**
     * Récupération du type d'objet Wordpress en relation.
     *
     * @return string
     */
    public function getObjectType();

    /**
     * Récupération de l'instance WP_Screen associée.
     *
     * @return \WP_Screen
     */
    public function getScreen();

    /**
     * Vérification de correspondance avec l'écran d'affichage courant.
     *
     * @return boolean
     */
    public function isCurrent();

    /**
     * Traitement des attributs de configuration.
     *
     * @return void
     */
    public function parse();
}