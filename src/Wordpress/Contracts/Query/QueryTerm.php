<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Query;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use tiFy\Contracts\Support\ParamsBag;
use tiFy\Wordpress\Contracts\Database\TaxonomyBuilder;
use WP_Term, WP_Term_Query;

/**
 * @property-read int $term_id
 * @property-read string $name
 * @property-read string $slug
 * @property-read string $term_group
 * @property-read int $term_taxonomy_id
 * @property-read string $taxonomy
 * @property-read string $description
 * @property-read int $parent
 * @property-read int $count
 * @property-read string $filter
 */
interface QueryTerm extends ParamsBag
{
    /**
     * Création d'une instance basée sur un objet post Wordpress et selon la cartographie des classes de rappel.
     *
     * @param WP_Term $wp_term
     *
     * @return static
     */
    public static function build(WP_Term $wp_term): QueryTerm;

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
     * Récupération d'une instance basée sur un modèle Laravel.
     *
     * @param EloquentModel $model
     *
     * @return static|null
     */
    public static function createFromEloquent(EloquentModel $model): ?QueryTerm;

    /**
     * Récupération d'une instance basée sur le terme global courant.
     *
     * @return static|null
     */
    public static function createFromGlobal(): ?QueryTerm;

    /**
     * Récupération d'une instance basée sur l'identifiant de qualification du terme.
     *
     * @param int $term_id Identifiant de qualification.
     *
     * @return static|null
     */
    public static function createFromId(int $term_id): ?QueryTerm;

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
     * Récupération d'une liste d'instances basée sur des arguments de requête de récupération des éléments.
     * @see https://developer.wordpress.org/reference/classes/wp_term_query/
     *
     * @param array $args Liste des arguments de la requête récupération des éléments.
     *
     * @return array
     */
    public static function fetchFromArgs(array $args = []): array;

    /**
     * Récupération d'une liste d'instances basée sur un resultat de requête en base de données.
     *
     * @param EloquentCollection $collection
     *
     * @return array
     */
    public static function fetchFromEloquent(EloquentCollection $collection): array;

    /**
     * Récupération d'une liste d'instances basée sur des identifiants de qualification de termes.
     * @see https://developer.wordpress.org/reference/classes/wp_term_query/
     *
     * @param int[] $ids Liste des identifiants de qualification.
     *
     * @return array
     */
    public static function fetchFromIds(array $ids): array;

    /**
     * Récupération d'une liste d'instances basée sur une instance de classe WP_Term_Query.
     * @see https://developer.wordpress.org/reference/classes/wp_term_query/
     *
     * @param WP_Term_Query $wp_term_query
     *
     * @return array
     */
    public static function fetchFromWpTermQuery(WP_Term_Query $wp_term_query): array;

    /**
     * Vérification d'intégrité d'une instance.
     *
     * @param QueryTerm|mixed $instance
     *
     * @return bool
     */
    public static function is($instance): bool;

    /**
     * Récupération de l'instance de pagination de la dernière requête de récupération d'une liste d'éléments.
     *
     * @return PaginationQuery
     */
    public static function pagination(): PaginationQuery;

    /**
     * Traitement d'arguments de requête de récupération des éléments.
     *
     * @param array $args Liste des arguments de la requête récupération des éléments.
     *
     * @return array
     */
    public static function parseQueryArgs(array $args = []) : array;

    /**
     * @param array $args
     *
     * @return array
     *
     * @see QueryTerm::fetchFromArgs()
     *
     * @deprecated
     */
    public static function queryFromArgs(array $args = []): array;

    /**
     * @param int[] $ids
     *
     * @return array
     *
     * @see QueryTerm::fetchFromIds()
     *
     * @deprecated
     */
    public static function queryFromIds(array $ids): array;

    /**
     * @param WP_Term_Query $wp_term_query
     *
     * @return array
     *
     * @see QueryTerm::fetchFromWpTermQuery()
     *
     * @deprecated
     */
    public static function queryFromWpTermQuery(WP_Term_Query $wp_term_query): array;

    /**
     * Définition d'une classe de rappel d'instanciation selon un type de post.
     *
     * @param string $taxonomy Nom de qualification du type de post associé.
     * @param string $classname Nom de qualification de la classe.
     *
     * @return void
     */
    public static function setBuiltInClass(string $taxonomy, string $classname): void;

    /**
     * Définition de la liste des arguments de requête de récupération des éléments.
     *
     * @param array $args
     *
     * @return void
     */
    public static function setDefaultArgs(array $args): void;

    /**
     * Définition de la classe de rappel par défaut.
     *
     * @param string $classname Nom de qualification de la classe.
     *
     * @return void
     */
    public static function setFallbackClass(string $classname): void;

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
    public function getMeta(string $meta_key, bool $single = false, $default = null);

    /**
     * Récupération d'une metadonnée de type multiple.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMetaMulti(string $meta_key, $default = null);

    /**
     * Récupération d'une metadonnée de type simple.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMetaSingle(string $meta_key, $default = null);

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

    /**
     * Vérification de correspondance de taxonomies.
     *
     * @param array|string $taxonomies Taxonomie(s) en correspondances.
     *
     * @return bool
     */
    public function taxIn($taxonomies): bool;
}