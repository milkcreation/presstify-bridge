<?php declare(strict_types=1);

namespace tiFy\Contracts\Taxonomy;

use tiFy\Contracts\Support\ParamsBag;

interface TaxonomyFactory extends ParamsBag
{
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
}