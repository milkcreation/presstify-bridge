<?php

namespace tiFy\Contracts\PostType;

use tiFy\Contracts\Kernel\ParamsBag;

/**
 * Interface PostQueryItem
 * @package tiFy\Contracts\PostType
 *
 * @deprecated
 */
interface PostQueryItem extends ParamsBag
{
    /**
     * Récupération de l'identifiant de qualification de l'auteur original.
     *
     * @return int
     */
    public function getAuthorId();

    /**
     * Récupération du contenu de description.
     *
     * @param bool $raw Formatage de la valeur.
     *
     * @return string
     */
    public function getContent($raw = false);

    /**
     * Récupération de la date de création au format datetime.
     *
     * @param bool $gmt Activation de la valeur basée sur le temps moyen de Greenwich.
     *
     * @return string
     */
    public function getDate($gmt = false);

    /**
     * Récupération du lien d'édition du post dans l'interface administrateur.
     *
     * @return string
     */
    public function getEditLink();

    /**
     * Récupération de la valeur brute ou formatée de l'extrait.
     *
     * @param bool $raw Formatage de la valeur.
     *
     * @return string
     */
    public function getExcerpt($raw = false);

    /**
     * Récupération de l'identifiant unique de qualification global.
     * @internal Ne devrait pas être utilisé en tant que lien.
     * @see https://developer.wordpress.org/reference/functions/the_guid/
     *
     * @return string
     */
    public function getGuid();

    /**
     * Récupération de l'identifiant de qualification Wordpress du post.
     *
     * @return int
     */
    public function getId();

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
     * Récupération de la date de la dernière modification au format datetime.
     *
     * @param bool $gmt Activation de la valeur basée sur le temps moyen de Greenwich.
     *
     * @return string
     */
    public function getModified($gmt = false);

    /**
     * Alias de récupération de l'identifiant de qualification Wordpress (post_name).
     *
     * @return string
     */
    public function getName();

    /**
     * Récupération de l'identifiant de qualification du post parent relatif.
     *
     * @return int
     */
    public function getParentId();

    /**
     * Récupération du permalien d'affichage du post dans l'interface utilisateur.
     *
     * @return string
     */
    public function getPermalink();

    /**
     * Récupération de l'object Post Wordpress associé.
     *
     * @return \WP_Post
     */
    public function getPost();

    /**
     * Récupération de l'identifiant de qualification Wordpress (post_name).
     *
     * @return string
     */
    public function getSlug();

    /**
     * Récupération du statut de publication.
     *
     * @return string
     */
    public function getStatus();

    /**
     * Récupération de la liste des termes de taxonomie.
     *
     * @param string|array $taxonomy Liste ou Nom de qualification de la taxonomie.
     * @param array $args Liste des arguments de récupération
     *
     * @return array|\WP_Term[]
     */
    public function getTerms($taxonomy, $args = []);

    /**
     * Récupération de l'url de l'image représentative.
     *
     * @param string|array $size Taille d'image déclaré|Tableau indexé [hauteur, largeur].
     * @param array $attrs Liste des attributs HTML de la balise img
     *
     * @return string
     */
    public function getThumbnail($size = 'post-thumbnail', $attrs = []);

    /**
     * Récupération de l'url de l'image représentative.
     *
     * @param string|array $size Taille d'image déclaré|Tableau indexé [hauteur, largeur].
     *
     * @return string
     */
    public function getThumbnailUrl($size = 'post-thumbnail');

    /**
     * Récupération de la valeur brute ou formatée de l'intitulé de qualification.
     *
     * @param bool $raw Formatage de la valeur.
     *
     * @return string
     */
    public function getTitle($raw = false);

    /**
     * Récupération du type de post.
     *
     * @return string
     */
    public function getType();
}