<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Query;

use Illuminate\Database\Eloquent\{
    Collection as EloquentCollection,
    Model as EloquentModel
};
use tiFy\Contracts\{PostType\PostTypeFactory, PostType\PostTypeStatus, Support\ParamsBag};
use tiFy\Support\DateTime;
use tiFy\Wordpress\Contracts\Database\PostBuilder;
use WP_Post, WP_Query, WP_Term, WP_User;

/**
 * @property-read int $ID
 * @property-read int $post_author
 * @property-read string $post_date
 * @property-read string $post_date_gmt
 * @property-read string $post_content
 * @property-read string $post_title
 * @property-read string $post_excerpt
 * @property-read string $post_status
 * @property-read string $comment_status
 * @property-read string $ping_status
 * @property-read string $post_password
 * @property-read string $post_name
 * @property-read string $to_ping
 * @property-read string $pinged
 * @property-read string $post_modified
 * @property-read string $post_modified_gmt
 * @property-read string $post_content_filtered
 * @property-read int $post_parent
 * @property-read string $guid
 * @property-read int $menu_order
 * @property-read string $post_type
 * @property-read string $post_mime_type
 * @property-read int $comment_count
 * @property-read string $filter
 */
interface QueryPost extends ParamsBag
{
    /**
     * Création d'une instance basée sur un objet post Wordpress et selon la cartographie des classes de rappel.
     *
     * @param WP_Post $wp_post
     *
     * @return static
     */
    public static function build(WP_Post $wp_post): QueryPost;

    /**
     * Création d'une instance basée sur un argument de qualification.
     *
     * @param int|string|WP_Post $id
     * @param array ...$args Liste des arguments de qualification complémentaires.
     *
     * @return static|null
     */
    public static function create($id = null, ...$args): ?QueryPost;

    /**
     * Récupération d'une instance basée sur un modèle Laravel.
     *
     * @param EloquentModel $model
     *
     * @return static|null
     */
    public static function createFromEloquent(EloquentModel $model): ?QueryPost;

    /**
     * Récupération d'une instance basée sur le post global courant.
     *
     * @return static|null
     */
    public static function createFromGlobal(): ?QueryPost;

    /**
     * Récupération d'une instance basée sur l'identifiant de qualification d'un post.
     *
     * @param int $post_id Identifiant de qualification.
     *
     * @return static|null
     */
    public static function createFromId(int $post_id): ?QueryPost;

    /**
     * Récupération d'une instance basée sur le nom de qualification d'un post.
     *
     * @param string $post_name Nom de qualification
     *
     * @return static|null
     */
    public static function createFromName(string $post_name): ?QueryPost;

    /**
     * Récupération d'une instance basée sur une liste de données de post.
     *
     * @param array $postdata Liste des données de post. ID requis.
     *
     * @return static|null
     */
    public static function createFromPostdata(array $postdata): ?QueryPost;

    /**
     * Récupération d'une liste des instances de posts courants|selon une requête WP_Query|selon une liste d'arguments.
     *
     * @param WP_Query|array|null $query
     *
     * @return QueryPost[]|array
     */
    public static function fetch($query = null): array;

    /**
     * Récupération d'une liste d'instances basée sur des arguments de requête de récupération des éléments.
     * @see https://developer.wordpress.org/reference/classes/wp_query/
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
     * Récupération d'une liste d'instances basée sur la requête de récupération globale.
     * @see https://developer.wordpress.org/reference/classes/wp_query/
     *
     * @return array
     */
    public static function fetchFromGlobal(): array;

    /**
     * Récupération d'une liste d'instances basée sur des identifiants de qualification de posts.
     * @see https://developer.wordpress.org/reference/classes/wp_query/
     *
     * @param int[] $ids Liste des identifiants de qualification.
     *
     * @return array
     */
    public static function fetchFromIds(array $ids): array;

    /**
     * Récupération d'une liste d'instances basée sur une instance de classe WP_Query.
     * @see https://developer.wordpress.org/reference/classes/wp_query/
     *
     * @param WP_Query $wp_query
     *
     * @return array
     */
    public static function fetchFromWpQuery(WP_Query $wp_query): array;

    /**
     * Vérification d'intégrité d'une instance.
     *
     * @param QueryPost|mixed $instance
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
    public static function parseQueryArgs(array $args = []): array;

    /**
     * @param array $args
     *
     * @return array
     *
     * @see QueryPost::fetchFromArgs()
     *
     * @deprecated
     */
    public static function queryFromArgs(array $args = []): array;

    /**
     * @param EloquentCollection $collection
     *
     * @return array
     *
     * @see QueryPost::fetchFromEloquent()
     *
     * @deprecated
     */
    public static function queryFromEloquent(EloquentCollection $collection): array;

    /**
     * @return array
     *
     * @see QueryPost::fetchFromGlobal()
     *
     * @deprecated
     */
    public static function queryFromGlobal(): array;

    /**
     * @param int[] $ids
     *
     * @return array
     *
     * @see QueryPost::fetchFromIds()
     *
     * @deprecated
     */
    public static function queryFromIds(array $ids): array;

    /**
     * @param WP_Query $wp_query
     *
     * @return array
     *
     * @see QueryPost::fetchFromWpQuery()
     *
     * @deprecated
     */
    public static function queryFromWpQuery(WP_Query $wp_query): array;

    /**
     * Définition d'une classe de rappel d'instanciation selon un type de post.
     *
     * @param string $post_type Nom de qualification du type de post associé.
     * @param string $classname Nom de qualification de la classe.
     *
     * @return void
     */
    public static function setBuiltInClass(string $post_type, string $classname): void;

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
     * Définition du type de post ou une liste de type de posts associés.
     *
     * @param string|array $post_type
     *
     * @return void
     */
    public static function setPostType($post_type): void;

    /**
     * Indicateur d'activation de la mise en cache.
     *
     * @return boolean
     */
    public function cacheable(): bool;

    /**
     * Ajout de données de cache associées au produit.
     *
     * @param string Clé d'indice de la données de cache.
     * @param mixed $value Valeur de retour par défaut
     *
     * @return static
     */
    public function cacheAdd(string $key, $value = null): QueryPost;

    /**
     * Suppression des données de cache associées au produit.
     *
     * @return static
     */
    public function cacheClear(): QueryPost;

    /**
     * Génération des données mise en cache.
     *
     * @return static
     */
    public function cacheCreate(): QueryPost;

    /**
     * Délai d'expiration du cache en secondes.
     * {@internal Une journée par défaut. Si null, le cache n'expire jamais.}
     *
     * @return int|null
     */
    public function cacheExpire(): ?int;

    /**
     * Récupération de données de post en cache.
     *
     * @param string|null Clé d'indice de la données de cache. Si null, retourne la liste complète des données.
     * @param mixed $default Valeur de retour par défaut
     *
     * @return mixed|array|string|boolean
     */
    public function cacheGet(?string $key = null, $default = null);

    /**
     * Vérification d'existance de données de post en cache.
     *
     * @param string Clé d'indice de la données de cache. Syntaxe à point permise.
     *
     * @return boolean
     */
    public function cacheHas(string $key): bool;

    /**
     * Définition de la clé d'indice d'enregistrement des données de post en cache.
     *
     * @return string
     */
    public function cacheKey(): string;

    /**
     * Récupération de l'instance du modèle de base de donnée associé.
     *
     * @return PostBuilder
     */
    public function db(): PostBuilder;

    /**
     * Url de la page d'archive associé.
     *
     * @return string|null
     */
    public function getArchiveUrl(): ?string;

    /**
     * Récupération de l'instance de l'auteur associé.
     *
     * @return QueryUser|null
     */
    public function getAuthor(): ?QueryUser;

    /**
     * Récupération de l'identifiant de qualification de l'auteur original.
     *
     * @return int
     */
    public function getAuthorId(): int;

    /**
     * Récupération de la liste des instance des enfants
     *
     * @param int|null $per_page Nombre d'élément par page. défaut -1. Si null utilise lé réglage posts_per_page.
     * @param int $page Page courante.
     * @param array $args Liste des arguments de requête complémentaires.
     *
     * @return static[]
     */
    public function getChilds(?int $per_page = -1, int $page = 1, array $args = []): array;

    /**
     * Récupération de la liste des classes HTML associées.
     *
     * @param string[] $classes Liste de classes complémentaires.
     * @param bool $html Activation du format de sortie de l'attribut de balise class. ex. class="post"
     *
     * @return string|array
     */
    public function getClass(array $classes = [], bool $html = true);

    /**
     * Récupération d'un commentaire associé.
     *
     * @param int $id Identifiant de qualification du commentaire.
     *
     * @return QueryComment|null
     */
    public function getComment(int $id): ?QueryComment;

    /**
     * Récupération de la liste des commentaires associé.
     * @see https://codex.wordpress.org/Class_Reference/WP_Comment_Query
     *
     * @param array $args Liste des argument de récupération.
     *
     * @return QueryComment[]|null
     */
    public function getComments(array $args = []): array;

    /**
     * Récupération du contenu de description.
     *
     * @param bool $raw Formatage de la valeur.
     *
     * @return string
     */
    public function getContent(bool $raw = false): string;

    /**
     * Récupération de la date de création au format datetime.
     *
     * @param bool $gmt Activation de la valeur basée sur le temps moyen de Greenwich.
     *
     * @return string
     */
    public function getDate(bool $gmt = false): string;

    /**
     * Récupération de l'objet DateTime basée sur la date création.
     *
     * @param bool $gmt Activation de la valeur basée sur le temps moyen de Greenwich.
     *
     * @return DateTime
     */
    public function getDateTime(bool $gmt = false): DateTime;

    /**
     * Récupération du lien d'édition du post dans l'interface administrateur.
     *
     * @return string
     */
    public function getEditUrl(): string;

    /**
     * Récupération de la valeur brute ou formatée de l'extrait.
     *
     * @param bool $raw Formatage de la valeur.
     *
     * @return string
     */
    public function getExcerpt(bool $raw = false): string;

    /**
     * Récupération de l'identifiant unique de qualification global.
     * {@internal Ne devrait pas être utilisé en tant que lien.}
     * @see https://developer.wordpress.org/reference/functions/the_guid/
     *
     * @return string
     */
    public function getGuid(): string;

    /**
     * Récupération de l'identifiant de qualification Wordpress du post.
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
     * Récupération de la liste des indices de métadonnées.
     *
     * @param boolean $registered Indicateur de récupération de indices de metadonnés déclarés.
     *
     * @return array
     */
    public function getMetaKeys(bool $registered = true): array;

    /**
     * Récupération d'une metadonnée de type multiple.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return string|array|mixed
     */
    public function getMetaMulti(string $meta_key, $default = null);

    /**
     * Récupération d'une metadonnée de type simple.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return string|array|mixed
     */
    public function getMetaSingle(string $meta_key, $default = null);

    /**
     * Récupération de la date de la dernière modification au format datetime.
     *
     * @param bool $gmt Activation de la valeur basée sur le temps moyen de Greenwich.
     *
     * @return string
     */
    public function getModified(bool $gmt = false): string;

    /**
     * Récupération de l'objet DateTime basée sur la date de modification.
     *
     * @param bool $gmt Activation de la valeur basée sur le temps moyen de Greenwich.
     *
     * @return DateTime
     */
    public function getModifiedDateTime(bool $gmt = false): DateTime;

    /**
     * Alias de récupération de l'identifiant de qualification Wordpress (post_name).
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération de l'instance tiFy du produit parent.
     *
     * @return static|null
     */
    public function getParent(): ?QueryPost;

    /**
     * Récupération de l'identifiant de qualification du post parent relatif.
     *
     * @return int
     */
    public function getParentId(): int;

    /**
     * Récupération du chenmin relatif vers l'affichage du post dans l'interface utilisateur.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Récupération du permalien d'affichage du post dans l'interface utilisateur.
     *
     * @return string
     */
    public function getPermalink(): string;

    /**
     * Récupération de l'identifiant de qualification Wordpress (post_name).
     *
     * @return string
     */
    public function getSlug(): string;

    /**
     * Récupération de l'instance du statut associé.
     *
     * @return PostTypeStatus
     */
    public function getStatus(): PostTypeStatus;

    /**
     * Récupération d'un contenu d'accroche basé sur l'extrait.
     *
     * @param int $length Nombre maximum de caractères de la chaîne.
     * @param string $teaser Délimiteur de fin de chaîne réduite (defaut : [...]).
     * @param boolean $use_tag Détection d'une balise d'arrêt du type <!--more-->.
     * @param boolean $uncut Préservation de la découpe de mots en fin de chaîne.
     *
     * @return string
     */
    public function getTeaser(
        int $length = 255,
        string $teaser = ' [&hellip;]',
        bool $use_tag = true,
        bool $uncut = true
    ): string;

    /**
     * Récupération de la liste des termes de taxonomie.
     *
     * @param string|array $taxonomy Liste ou Nom de qualification de la taxonomie.
     * @param array $args Liste des arguments de récupération
     *
     * @return WP_Term[]|array
     */
    public function getTerms($taxonomy, array $args = []): array;

    /**
     * Récupération de l'image représentative.
     *
     * @param string|array $size Taille d'image déclaré|Tableau indexé [hauteur, largeur].
     * @param array $attrs Liste des attributs HTML de la balise img
     *
     * @return string
     */
    public function getThumbnail($size = 'post-thumbnail', array $attrs = []): string;

    /**
     * Récupération de la source base64 d'une image.
     *
     * @param string|array $size Taille de l'image. Nom de qualification (full|large|thumbnail|...)|taille perso [w,h].
     *
     * @return string|null
     */
    public function getThumbnailBase64Src($size = 'thumbnail'): ?string;

    /**
     * Récupération de l'url de l'image représentative.
     *
     * @param string|array $size Taille d'image déclaré|Tableau indexé [hauteur, largeur].
     *
     * @return string
     */
    public function getThumbnailSrc($size = 'post-thumbnail'): string;

    /**
     * Récupération de la valeur brute ou formatée de l'intitulé de qualification.
     *
     * @param bool $raw Formatage de la valeur.
     *
     * @return string
     */
    public function getTitle(bool $raw = false): string;

    /**
     * Récupération du type de post.
     *
     * @return PostTypeFactory|null
     */
    public function getType(): ?PostTypeFactory;

    /**
     * Récupération de l'instance de post Wordpress associée.
     *
     * @return WP_Post|null
     */
    public function getWpPost(): ?WP_Post;

    /**
     * Vérification d'existance de terme(s) de taxonomie pour le post associé.
     *
     * @param string|int|array Nom de qualification|Identifiant de qualification|Slug du terme ou liste de terme.
     * @param string $taxonomy Nom de qualification de la taxonomie.
     *
     * @return boolean
     */
    public function hasTerm($term, string $taxonomy): bool;

    /**
     * Sauvegarde des données du post en base.
     *
     * @param array $postdata Liste des données à enregistrer
     *
     * @return void
     */
    public function save(array $postdata): void;

    /**
     * Sauvegarde (Ajout ou mise à jour) d'un commentaire associé au post.
     *
     * @param string $content Contenu du commentaire.
     * @param array $commentdata
     * @param WP_User|null $wp_user
     *
     * @return int
     */
    public function saveComment(string $content, array $commentdata = [], ?WP_User $wp_user = null): int;

    /**
     * Sauvegarde (Ajout ou mise à jour) de metadonnées du post en base.
     *
     * @param string|array $key Indice de métadonnées ou tableau associatif clé/valeur.
     * @param mixed $value Valeur de la métadonnées si key est un indice.
     *
     * @return void
     */
    public function saveMeta($key, $value = null): void;

    /**
     * Vérification de correspondance de type de post.
     *
     * @param array|string $post_types Type(s) de post en correspondance.
     *
     * @return bool
     */
    public function typeIn($post_types): bool;
}