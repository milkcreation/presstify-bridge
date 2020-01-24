<?php declare(strict_types=1);

namespace tiFy\PostType;

use tiFy\Support\LabelsBag;

/**
 * @see https://codex.wordpress.org/Function_Reference/register_post_type
 */
class PostTypeLabelsBag extends LabelsBag
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'name'               => $this->plural(true),
            'singular_name'      => $this->singular(true),
            'add_new'            => !$this->gender()
                ? sprintf(__('Ajouter un %s', 'tify'), $this->singular())
                : sprintf(__('Ajouter une %s', 'tify'), $this->singular()),
            'add_new_item'       => !$this->gender()
                ? sprintf(__('Ajouter un %s', 'tify'), $this->singular())
                : sprintf(__('Ajouter une %s', 'tify'), $this->singular()),
            'edit_item'          => sprintf(__('Éditer %s', 'tify'), $this->singularDefinite()),
            'new_item'           => !$this->gender()
                ? sprintf(__('Créer un %s', 'tify'), $this->singular())
                : sprintf(__('Créer une %s', 'tify'), $this->singular()),
            'view_item'          => !$this->gender()
                ? sprintf(__('Voir cet %s', 'tify'), $this->singular())
                : sprintf(__('Voir cette %s', 'tify'), $this->singular()),
            'view_items'         => sprintf(__('Voir ces %s', 'tify'), $this->plural()),
            'search_items'       => !$this->gender()
                ? sprintf(__('Rechercher un %s', 'tify'), $this->singular())
                : sprintf(__('Rechercher une %s', 'tify'), $this->singular()),
            'not_found'          => !$this->gender()
                ? sprintf(__('Aucun %s trouvé', 'tify'), $this->singular(true))
                : sprintf(__('Aucune %s trouvée', 'tify'), $this->singular(true)),
            'not_found_in_trash' => !$this->gender()
                ? sprintf(__('Aucun %s dans la corbeille', 'tify'), $this->singular(true))
                : sprintf(__('Aucune %s dans la corbeille', 'tify'), $this->singular(true)),
            'parent_item_colon'  => sprintf(__('%s parent', 'tify'), $this->singular(true)),
            'all_items'          => !$this->gender()
                ? sprintf(__('Tous les %s', 'tify'), $this->plural())
                : sprintf(__('Toutes les %s', 'tify'), $this->plural()),
            'archives'           => !$this->gender()
                ? sprintf(__('Tous les %s', 'tify'), $this->plural())
                : sprintf(__('Toutes les %s', 'tify'), $this->plural()),
            'attributes'         => !$this->gender()
                ? sprintf(__('Tous les %s', 'tify'), $this->plural())
                : sprintf(__('Toutes les %s', 'tify'), $this->plural()),
            // @todo 'insert_into_item' => ''
            // @todo 'uploaded_to_this_item' => ''
            // @todo 'featured_image' => ''
            // @todo 'set_featured_image' => ''
            // @todo 'remove_featured_image' => ''
            // @todo 'use_featured_image' => ''
            'menu_name'          => _x($this->plural(true), 'admin menu', 'tify'),
            // @todo 'filter_items_list' => ''
            // @todo 'items_list_navigation' => ''
            // @todo 'items_list' => ''
            // @todo 'filter_items_list' => ''
            'name_admin_bar'     => _x($this->singular(true), 'add new on admin bar', 'tify'),
            /* @todo
             * 'datas_item'                 => $this->defaultDatasItem(),
             * 'import_items'               => sprintf(__('Importer des %s', 'tify'), $this->plural()),
             * 'export_items'               => sprintf(__('Export des %s', 'tify'), $this->plural()),
             */
        ];
    }
}