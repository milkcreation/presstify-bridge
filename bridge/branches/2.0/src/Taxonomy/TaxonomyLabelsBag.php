<?php declare(strict_types=1);

namespace tiFy\Taxonomy;

use tiFy\Support\LabelsBag;

/**
 * @see https://codex.wordpress.org/Function_Reference/register_taxonomy
 */
class TaxonomyLabelsBag extends LabelsBag
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'name'                       => $this->plural(true),
            'singular_name'              => $this->singular(true),
            'menu_name'                  => _x($this->plural(true), 'admin menu', 'tify'),
            'all_items'                  => !$this->gender()
                ? sprintf(__('Tous les %s', 'tify'), $this->plural())
                : sprintf(__('Toutes les %s', 'tify'), $this->plural()),
            'edit_item'                  => sprintf(__('Éditer %s', 'tify'), $this->singularDefinite()),
            'view_item'                  => !$this->gender()
                ? sprintf(__('Voir cet %s', 'tify'), $this->singular())
                : sprintf(__('Voir cette %s', 'tify'), $this->singular()),
            'update_item'                => !$this->gender()
                ? sprintf(__('Mettre à jour ce %s', 'tify'), $this->singular())
                : sprintf(__('Mettre à jour cette %s', 'tify'), $this->singular()),
            'add_new_item'               => !$this->gender()
                ? sprintf(__('Ajouter un %s', 'tify'), $this->singular())
                : sprintf(__('Ajouter une %s', 'tify'), $this->singular()),
            'new_item_name'              => !$this->gender()
                ? sprintf(__('Créer un %s', 'tify'), $this->singular())
                : sprintf(__('Créer une %s', 'tify'), $this->singular()),
            'parent_item'                => sprintf(__('%s parent', 'tify'), $this->singular(true)),
            'parent_item_colon'          => sprintf(__('%s parent', 'tify'), $this->singular(true)),
            'search_items'               => !$this->gender()
                ? sprintf(__('Rechercher un %s', 'tify'), $this->singular())
                : sprintf(__('Rechercher une %s', 'tify'), $this->singular()),
            'popular_items'              => sprintf(__('%s populaires', 'tify'), $this->plural(true)),
            'separate_items_with_commas' => sprintf(__('Séparer les %s par une virgule', 'tify'), $this->plural()),
            'add_or_remove_items'        => sprintf(__('Ajouter ou supprimer des %s', 'tify'), $this->plural()),
            'choose_from_most_used'      => !$this->gender()
                ? sprintf(__('Choisir parmi les %s les plus utilisés', 'tify'), $this->plural())
                : sprintf(__('Choisir parmi les %s les plus utilisées', 'tify'), $this->plural()),
            'not_found'                  => !$this->gender()
                ? sprintf(__('Aucun %s trouvé', 'tify'), $this->singular(true))
                : sprintf(__('Aucune %s trouvée', 'tify'), $this->singular(true)),
            /* @todo
             * 'datas_item'                 => $this->defaultDatasItem(),
             * 'import_items'               => sprintf(__('Importer des %s', 'tify'), $this->plural()),
             * 'export_items'               => sprintf(__('Export des %s', 'tify'), $this->plural()),
             */
        ];
    }
}