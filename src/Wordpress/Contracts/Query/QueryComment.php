<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Query;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Support\DateTime;
use WP_Comment;
use WP_Comment_Query;

interface QueryComment extends ParamsBag
{
    /**
     * Création d'une instance basée sur l'identifiant de qualification d'un commentaire.
     *
     * @param int $comment_id Identifiant de qualification de commentaire.
     *
     * @return static
     */
    public static function createFromId(int $comment_id): ?QueryComment;

    /**
     * Récupération d'une liste d'instances basée sur des arguments de requête de récupération des éléments.
     * @see https://developer.wordpress.org/reference/classes/wp_comment_query/
     *
     * @param array $args Liste des arguments de la requête récupération des éléments.
     *
     * @return array
     */
    public static function fetchFromArgs(array $args = []): array;

    /**
     * Récupération d'une liste d'instances basée sur des identifiants de qualification de commentaires.
     * @see https://developer.wordpress.org/reference/classes/wp_comment_query/
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
     * @param WP_Comment_Query $wp_comment_query
     *
     * @return array
     */
    public static function fetchFromWpCommentQuery(WP_Comment_Query $wp_comment_query): array;

    /**
     * Traitement d'arguments de requête de récupération des éléments.
     *
     * @param array $args Liste des arguments de la requête récupération des éléments.
     *
     * @return array
     */
    public static function parseQueryArgs(array $args = []): array;

    /**
     * Récupération de l'instance de la dernière requête de récupération d'une liste d'éléments.
     *
     * @return ParamsBag
     */
    public static function query(): ParamsBag;

    /**
     * {@inheritDoc}
     *
     * @deprecated
     */
    public static function queryFromArgs(array $args = []): array;

    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public static function queryFromIds(array $ids): array;

    /**
     * Récupération des informations système (navigateur, système d'exploitation ...).
     *
     * @return string
     */
    public function getAgent(): string;

    /**
     * Récupération du nom de l'auteur.
     *
     * @return string
     */
    public function getAuthor(): string;

    /**
     * Récupération de l'email de l'auteur.
     *
     * @return string
     */
    public function getAuthorEmail(): string;

    /**
     * Récupération de l'adresse IP de l'auteur.
     *
     * @return string
     */
    public function getAuthorIp(): string;

    /**
     * Récupération de l'url de site web de l'auteur.
     *
     * @return string
     */
    public function getAuthorUrl(): string;

    /**
     * Récupération du contenu du message.
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Récupération de la date de création.
     *
     * @param boolean $gmt Indicateur de récupération de la date GMT.
     *
     * @return string
     */
    public function getDate(bool $gmt = false): string;

    /**
     * Récupération d'un objet DateTime basée sur la date création.
     *
     * @param boolean $gmt Indicateur de récupération de la date GMT.
     *
     * @return DateTime
     */
    public function getDateTime(bool $gmt = false): DateTime;

    /**
     * Récupération de l'url d'édition du commentaire.
     *
     * @return string
     */
    public function getEditUrl(): string;

    /**
     * Récupération de l'identifiant de qualification.
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Récupération de métadonnée.
     *
     * @param string $meta_key Clé d'indice.
     * @param bool $single Indicateur de metadonnée simple.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMeta(string $meta_key, bool $single = false, $default = null);

    /**
     * Récupération de métadonnée multiple.
     *
     * @param string $meta_key Clé d'indice.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMetaMulti(string $meta_key, $default = null);

    /**
     * Récupération de métadonnée simple.
     *
     * @param string $meta_key Clé d'indice.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMetaSingle(string $meta_key, $default = null);

    /**
     * Récupération de l'instance du commentaire parent.
     *
     * @return static|null
     */
    public function getParent(): ?QueryComment;

    /**
     * Récupération de l'identifiant de qualification du commentaire parent.
     *
     * @return int
     */
    public function getParentId(): int;

    /**
     * Récupération de l'instance du post associé.
     *
     * @return QueryPost
     */
    public function getPost(): QueryPost;

    /**
     * Récupération de l'identifiant de qualification du post associé.
     *
     * @return int
     */
    public function getPostId(): int;

    /**
     * Récupération du type de commentaire.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Récupération de l'instance de l'utilisateur associé.
     *
     * @return QueryUser
     */
    public function getUser(): QueryUser;

    /**
     * Récupération de l'identifiant de qualification de l'utilisateur associé (auteur).
     *
     * @return int
     */
    public function getUserId(): int;

    /**
     * Récupération de l'instance de commentaire Wordpress.
     *
     * @return WP_Comment
     */
    public function getWpComment(): WP_Comment;

    /**
     * Vérification d'approbation du commentaire.
     *
     * @return boolean
     */
    public function isApproved(): bool;

    /**
     * Vérifie si le commentaire est considéré comme spam.
     *
     * @return boolean
     */
    public function isSpam(): bool;

    /**
     * Vérification de correspondance du type de commentaire.
     *
     * @param string[] $comment_types Liste des types de correspondance à vérifier.
     *
     * @return boolean
     */
    public function typeIn(array $comment_types): bool;
}