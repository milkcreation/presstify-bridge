<?php

namespace tiFy\Contracts\PostType;

interface PostTypePostMeta
{
    /**
     * Ajout d'une metadonnée.
     *
     * @param int $post_id Identifiant de qualification du post.
     * @param string $meta_key Clé d'index de la metadonnée.
     * @param mixed $meta_value Valeur de la métadonnée à ajouter.
     *
     * @return bool|int
     */
    public function add($post_id, $meta_key, $meta_value);

    /**
     * Récupération d'une métadonnée.
     *
     * @param int $post_id Identifiant de qualification du post.
     * @param string $meta_key Clé d'index de la metadonnée.
     *
     * @return mixed[]
     */
    public function get($post_id, $meta_key);

    /**
     * Vérifie si une métadonnées déclarée est de type single ou multi.
     *
     * @param string $post_type Type de post.
     * @param string $meta_key Clé d'index de la metadonnée.
     *
     * @return bool
     */
    public function isSingle($post_type, $meta_key);

    /**
     * Récupération de la liste des clés d'identification de métadonnées déclarées.
     *
     * @param string|null $post_type Nom de qualification du type de post
     *
     * @return array
     */
    public function keys(?string $post_type = ''): array;

    /**
     * Déclaration d'une métadonnée.
     *
     * @param string $post_type Type de post.
     * @param string $meta_key Clé d'index de la metadonnée.
     * @param bool $single Indicateur d'enregistrement de la métadonnée unique (true)|multiple (false).
     * @param string $sanitize_callback Méthode ou fonction de rappel avant l'enregistrement.
     *
     * @return $this
     */
    public function register($post_type, $meta_key, $single = false, $sanitize_callback = 'wp_unslash');

    /**
     * Enregistrement de metadonnées de post.
     *
     * @param int $post_id Identifiant de qualification du post.
     * @param \WP_Post $post Objet Post Wordpress.
     *
     * @return void
     */
    public function save($post_id, $post);

    /**
     * Mise à jour d'une metadonnée.
     *
     * @param int $post_id Identifiant de qualification du post.
     * @param string $meta_key Clé d'index de la metadonnée.
     * @param mixed $meta_value Valeur de la métadonnée à mettre à jour.
     *
     * @return bool|int
     */
    public function update($post_id, $meta_key, $meta_value);
}