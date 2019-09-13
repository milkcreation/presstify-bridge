<?php

namespace tiFy\Taxonomy\Metabox\Icon;

use tiFy\Metabox\MetaboxWpTermController;

class Icon extends MetaboxWpTermController
{
    /**
     * Chargement de la page courante
     *
     * @param \WP_Screen $current_screen
     *
     * @return void
     */
    public function current_screen($current_screen)
    {
        // Traitement des arguments
        $this->args = wp_parse_args(
            $this->args,
            [
                'name' => '_icon',
                'dir'  => '',
            ]
        );
        $this->args['dir'] = wp_normalize_path(rtrim($this->args['dir'], '/'));

        tify_meta_term_register($current_screen->taxonomy, $this->args['name'], true);
    }

    /**
     * Mise en file des scripts de l'interface d'administration
     *
     * @return void
     */
    public function admin_enqueue_scripts()
    {
        wp_enqueue_style('tify_control-dropdown_images');
        wp_enqueue_script('tify_control-dropdown_images');
    }

    /**
     * CONTROLEURS
     */
    /**
     * Formulaire de saisie
     */
    public function form($term, $taxonomy)
    {
        $choices = [];
        foreach ((array)glob($this->args['dir'] . '/*') as $filename) :
            $name = basename($filename);
            $url = site_url('/') . \tiFy\Lib\File::getRelativeFilename($filename);
            $choices[$name] = $url;
        endforeach;

        DropdownImages::display(
            [
                'name'     => "tify_meta_term[{$this->args['name']}]",
                'choices'  => $choices,
                'selected' => get_term_meta($term->term_id, $this->args['name'], true),
            ]
        );
    }
}