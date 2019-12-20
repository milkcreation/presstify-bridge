<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Query;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Wordpress\Contracts\Database\TaxonomyBuilder;
use WP_Term;
use WP_Term_Query;

interface QueryTerm extends ParamsBag
{
    /**
     * Création d'un instance basée sur un argument de qualification.
     *
     * @param int|string|WP_Term $id
     * @param array ...$args Liste des arguments de qualification complémentaires.
     *
     * @return static|null
     */
    public static function create($id = null, ...$args): ?QueryTerm;

    /**
     * Récupération d'une instance basée sur l'identifiant de qualification du terme.
     *
     * @param int $term_id
     *
     * @return static|null
     */
    public static function createFromId(int $term_id): ?QueryTerm;

    /**
     * Récupération d'une instance basée sur le terme global courant.
     *
     * @return static|null
     */
    public static function createFromGlobal(): ?QueryTerm;

    /**
     * Récupération d'une instance basée sur le nom de qualification du terme.
     *
     * @param string $term_slug
     * @param string|null $taxonomy Nom de qualification de la taxonomie associée.
     *
     * @return static|null
     */
    public static function createFromSlug(string $term_slug, ?string $taxonomy = null): ?QueryTerm;

    /**
     * Récupération d'une liste des instances des termes courants|selon une requête WP_Term_Query|selon une liste d'arguments.
     *
     * @param WP_Term_Query|array $query
     *
     * @return QueryPost[]|array
     */
    public static function fetch($query): array;

    /**
     * Traitement d'arguments de requête de récupération des éléments.
     *
     * @param array $args Liste des arguments de la requête récupération des éléments.
     *
     * @return array
     */
    public static function parseQueryArgs(array $args = []) : array;

    /**
     * Récupération de l'instance de la dernière requête de récupération d'une liste d'éléments.
     *
     * @return ParamsBag
     */
    public static function query(): ParamsBag;

    /**
     * Récupération d'une liste d'instances basée sur des arguments de requête de récupération des éléments.
     * @see https://developer.wordpress.org/reference/classes/wp_term_query/
     *
     * @param array $args Liste des arguments de la requête récupération des éléments.
     *
     * @return array
     */
    public static function queryFromArgs(array $args = []): array;

    /**
     * Récupération d'une liste d'instances basée sur des identifiants de qualification de termes.
     * @see https://developer.wordpress.org/reference/classes/wp_term_query/
     *
     * @param int[] $ids Liste des identifiants de qualification.
     *
     * @return array
     */
    public static function queryFromIds(array $ids): array;

    /**
     * Récupération d'une liste d'instances basée sur une instance de classe WP_Term_Query.
     * @see https://developer.wordpress.org/reference/classes/wp_term_query/
     *
     * @param WP_Term_Query $wp_term_query
     *
     * @return array
     */
    public static function queryFromWpTermQuery(WP_Term_Query $wp_term_query): array;

    /**
     * Définition de la liste des arguments de requête de récupération des éléments.
     *
     * @param array $args
     *
     * @return void
     */
    public static function setDefaultArgs(array $args): void;

    /**
     * Définition de la taxonomie associée.
     *
     * @param string $taxonomy
     *
     * @return void
     */
    public static function setTaxonomy(string $taxonomy): void;

    /**
     * Récupération de l'instance du modèle de base de donnée associé.
     *
     * @return TaxonomyBuilder
     */
    public function db(): TaxonomyBuilder;

    /**
     * Récupération de la description.
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Récupération de l'identifiant de qualification Wordpress du terme.
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Récupération d'une metadonnée.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param bool $single Type de metadonnés. single (true)|multiple (false). false par défaut.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMeta($meta_key, $single = false, $default = null);

    /**
     * Récupération d'une metadonnée de type multiple.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMetaMulti($meta_key, $default = null);

    /**
     * Récupération d'une metadonnée de type simple.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMetaSingle($meta_key, $default = null);

    /**
     * Récupération de l'intitulé de qualification.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération du permalien d'affichage de la liste de élément associés au terme.
     *
     * @return string
     */
    public function getPermalink(): string;

    /**
     * Récupération du nom de qualification Wordpress du terme.
     *
     * @return string
     */
    public function getSlug(): string;

    /**
     * Récupération de la taxonomie relative.
     *
     * @return string
     */
    public function getTaxonomy(): string;

    /**
     * Récupération de l'object Terme Wordpress associé.
     *
     * @return WP_Term
     */
    public function getWpTerm(): WP_Term;

    /**
     * Sauvegarde des données du terme en base.
     *
     * @param array $termdata Liste des données à enregistrer.
     *
     * @return void
     */
    public function save(array $termdata): void;

    /**
     * Sauvegarde (Ajout ou mise à jour) de metadonnées du terme en base.
     *
     * @param string|array $key Indice de métadonnées ou tableau associatif clé/valeur.
     * @param mixed $value Valeur de la métadonnées si key est un indice.
     *
     * @return void
     */
    public function saveMeta($key, $value = null): void;
}