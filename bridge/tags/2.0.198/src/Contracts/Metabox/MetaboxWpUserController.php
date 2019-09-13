<?php

namespace tiFy\Contracts\Metabox;

interface MetaboxWpUserController extends MetaboxController
{
    /**
     * Affichage du contenu.
     *
     * @param \WP_User $user Objet utilisateur Wordpress.
     * @param array $args Liste des variables passés en argument.
     * @param null $null Paramètre indisponible.
     *
     * @return string
     */
    public function content($user = null, $args = null, $null = null);

    /**
     * Affichage de l'entête.
     *
     * @param \WP_User $user Objet utilisateur Wordpress.
     * @param array $args Liste des variables passés en argument.
     * @param null $null Paramètre indisponible.
     *
     * @return string
     */
    public function header($user = null, $args = null, $null = null);
}