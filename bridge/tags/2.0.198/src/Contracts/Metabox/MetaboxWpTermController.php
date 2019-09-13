<?php

namespace tiFy\Contracts\Metabox;

interface MetaboxWpTermController extends MetaboxController
{
    /**
     * Affichage du contenu.
     *
     * @param \WP_Term $term Objet du terme courant Wordpress.
     * @param string $taxonomy Nom de de qualification de la taxonomie associée au terme.
     * @param array $args Liste des variables passés en argument.
     *
     * @return string
     */
    public function content($term = null, $taxonomy = null, $args = null);

    /**
     * Récupération de la taxonomie de l'environnement d'affichage de la page d'administration.
     *
     * @return string category|tag|{{custom_taxonomy}}
     */
    public function getTaxonomy();

    /**
     * Affichage de l'entête.
     *
     * @param \WP_Term $term Objet du terme courant Wordpress.
     * @param string $taxonomy Nom de de qualification de la taxonomie associée au terme.
     * @param array $args Liste des variables passés en argument.
     *
     * @return string
     */
    public function header($term = null, $taxonomy = null, $args = null);

    /**
     * Listes des metadonnées à enregistrer.
     *
     * @return array
     */
    public function metadatas();
}