<?php declare(strict_types=1);

namespace tiFy\Contracts\Taxonomy;

use tiFy\Contracts\Support\ParamsBag;
use WP_Taxonomy;

/**
 * @property-read string $label
 * @property-read object $labels
 * @property-read string $description
 * @property-read bool $public
 * @property-read bool $publicly_queryable
 * @property-read bool $hierarchical
 * @property-read bool $show_ui
 * @property-read bool $show_in_menu
 * @property-read bool $show_in_nav_menus
 * @property-read bool $show_tagcloud
 * @property-read bool show_in_quick_edit
 * @property-read bool $show_admin_column
 * @property-read bool|callable $meta_box_cb
 * @property-read callable $meta_box_sanitize_cb
 * @property-read array $object_type
 * @property-read object $cap
 * @property-read array|false $rewrite
 * @property-read string|false $query_var
 * @property-read callable $update_count_callback
 * @property-read bool $show_in_rest
 * @property-read string|bool $rest_base
 * @property-read string|bool  $rest_controller_class
 * @property-read bool  $_builtin
 */
interface TaxonomyFactory extends ParamsBag
{
    /**
     * Récupération des données de délégation de la Taxonomie Wordpress associée.
     *
     * @param int|string $key
     *
     * @return mixed
     */
    public function __get($key);

    /**
     * Résolution de sortie de la classe sous forme de chaîne de caractère.
     * {@internal Retourne le nom de qualification de la taxonomie.}
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Récupération du nom de qualification du type de post.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération d'un intitulé.
     *
     * @param string $key Clé d'indice de l'intitulé.
     * @see https://codex.wordpress.org/Function_Reference/register_taxonomy
     * plural|singular|name|singular_name|menu_name|all_items|edit_item|view_item|update_item|add_new_item|
     * new_item_name|parent_item|parent_item_colon|search_items|popular_items|separate_items_with_commas|
     * add_or_remove_items|choose_from_most_used|not_found|back_to_items
     * @param string $default Valeur de retour par défaut.
     *
     * @return string
     */
    public function label(string $key, string $default = ''): string;

    /**
     * Définition de métadonnée de terme.
     *
     * @param string|array $key Indice de la métadonnée ou tableau indexé ou tableau associatif.
     * @param bool $single Indicateur de donnée unique. Valeur par défaut des déclarations par tableau indexé.
     *
     * @return static
     */
    public function meta($key, bool $single = true): TaxonomyFactory;

    /**
     * Initialisation du controleur.
     *
     * @return static
     */
    public function prepare(): TaxonomyFactory;

    /**
     * Définition de l'instance du gestionnaire de taxonomies.
     *
     * @param Taxonomy $manager
     *
     * @return static
     */
    public function setManager(Taxonomy $manager): TaxonomyFactory;

    /**
     * Définition de l'instance de la taxonomie Wordpress associée.
     *
     * @param WP_Taxonomy $taxonomy
     *
     * @return static
     */
    public function setWpTaxonomy(WP_Taxonomy $taxonomy): TaxonomyFactory;
}