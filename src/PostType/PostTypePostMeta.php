<?php declare(strict_types=1);

namespace tiFy\PostType;

use tiFy\Contracts\PostType\PostTypePostMeta as PostTypePostMetaContract;

class PostTypePostMeta implements PostTypePostMetaContract
{
    /**
     * Liste des clés d'identifications de metadonnées.
     * @internal Tableau multidimensionné où la clé de l'index de premier niveau qualifie le type de post associé.
     *
     * @var array
     */
    protected $metaKeys = [];

    /**
     * Liste des types d'enregistrement unique (true)|multiple (false) d'une metadonnée.
     * @internal Tableau multidimensionné où la clé de l'index de premier niveau qualifie le type de post associé.
     *
     * @var array
     */
    protected $single = [];

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('save_post', [$this, 'save'], 10, 2);
    }

    /**
     * @inheritdoc
     */
    public function add($post_id, $meta_key, $meta_value)
    {
        if (!$post_type = get_post_type($post_id)) :
            return false;
        endif;

        $unique = $this->isSingle($post_type, $meta_key);

        if ($unique === null):
            return false;
        endif;

        return add_post_meta($post_id, $meta_key, $meta_value, $unique);
    }

    /**
     * @inheritdoc
     */
    public function get($post_id, $meta_key)
    {
        global $wpdb;
        $query = "SELECT meta_id, meta_value" . " FROM {$wpdb->postmeta}" .
            " WHERE 1" . " AND {$wpdb->postmeta}.post_id = %d" . " AND {$wpdb->postmeta}.meta_key = %s";

        if ($order = get_post_meta($post_id, '_order_' . $meta_key, true)) :
            $query .= " ORDER BY FIELD( {$wpdb->postmeta}.meta_id," . implode(',', $order) . ")";
        endif;

        if (!$metas = $wpdb->get_results($wpdb->prepare($query, $post_id, $meta_key))) :
            return [];
        endif;

        $_metas = [];
        foreach ((array)$metas as $index => $args) :
            $_metas[$args->meta_id] = maybe_unserialize($args->meta_value);
        endforeach;

        return $_metas;
    }

    /**
     * @inheritdoc
     */
    public function isSingle($post_type, $meta_key)
    {
        return isset($this->single[$post_type][$meta_key]) ? $this->single[$post_type][$meta_key] : false;
    }

    /**
     * @inheritdoc
     */
    public function keys(?string $post_type = ''): array
    {
       return $post_type ? ($this->metaKeys[$post_type] ?? []) : $this->metaKeys;
    }

    /**
     * @inheritdoc
     */
    public function register($post_type, $meta_key, $single = false, $sanitize_callback = 'wp_unslash')
    {
        // Bypass
        if (! empty($this->metaKeys[$post_type]) && in_array($meta_key, $this->metaKeys[$post_type])) :
            return $this;
        endif;

        $this->metaKeys[$post_type][] = $meta_key;
        $this->single[$post_type][$meta_key] = $single;

        if ($sanitize_callback !== '') :
            add_filter("tify_sanitize_meta_post_{$post_type}_{$meta_key}", $sanitize_callback);
        endif;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function save($post_id, $post)
    {
        // Bypass - S'il s'agit d'une routine de sauvegarde automatique.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        // Bypass - Si le script est executé via Ajax.
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }

        // Bypass - Si l'argument de requête renseignant l'indication de type de post est manquant.
        if (! $post_type = request()->post('post_type', '')) {
            return;
        }

        // Bypass - Si l'utilisateur courant n'est pas habilité  à modifié le contenu.
        if (('page' === $post_type) && ! current_user_can('edit_page', $post_id)) {
            return;
        }

        if (('page' !== $post_type) && ! current_user_can('edit_post', $post_id)) {
            return;
        }

        // Bypass - Si la vérification de l'existance du post est en échec.
        if (! $post = get_post($post_id)) {
            return;
        }

        // Bypass - Si le type de post définit dans la requête est différent du type de post du contenu a éditer.
        if ($post_type !== $post->post_type) {
            return;
        }

        // Bypass - Si aucune métadonnée n'est déclarée pour le type de post.
        if (empty($this->metaKeys[$post_type])) {
            return;
        }

        // Déclaration des variables
        $meta_keys = $this->metaKeys[$post_type];
        $postmeta = [];
        $meta_ids = [];
        $meta_exists = [];
        $request = [];

        // Récupération des metadonnés en requête $_POST
        foreach ($this->metaKeys[$post_type] as $key) {
            if ($value = request()->post($key)) {
                $request[$key] = $value;
            }
        }

        foreach ($meta_keys as $meta_key) {
            // Vérification d'existance de la metadonnées en base
            if ($_meta = $this->get($post_id, $meta_key)) :
                $meta_exists += $_meta;
            endif;

            if (!isset($request[$meta_key])) :
                continue;
            endif;

            // Récupération des meta_ids de metadonnées unique
            if ($this->isSingle($post_type, $meta_key)) :
                $meta_id = $_meta ? key($_meta) : uniqid();
                array_push($meta_ids, $meta_id);
                $postmeta[$meta_key][$meta_id] = $request[$meta_key];

            // Récupération des meta_ids de metadonnées multiple
            elseif ($this->isSingle($post_type, $meta_key) === false) :
                $meta_ids += array_keys($request[$meta_key]);
                $postmeta[$meta_key] = $request[$meta_key];
            endif;
        }

        // Suppression des metadonnées absente du processus de sauvegarde
        foreach ($meta_exists as $meta_id => $meta_value) :
            if (! in_array($meta_id, $meta_ids)) :
                delete_metadata_by_mid('post', $meta_id);
            endif;
        endforeach;

        // Sauvegarde des metadonnées (mise à jour ou ajout)
        foreach ($meta_keys as $meta_key) :
            if (! isset($postmeta[$meta_key])) :
                continue;
            endif;

            $order = [];
            foreach ((array)$postmeta[$meta_key] as $meta_id => $meta_value) :
                $meta_value = apply_filters("tify_sanitize_meta_post_{$post_type}_{$meta_key}", $meta_value);

                if (is_int($meta_id) && get_post_meta_by_id($meta_id)) :
                    $_meta_id = $meta_id;
                    update_metadata_by_mid('post', $meta_id, $meta_value);
                else :
                    $_meta_id = add_post_meta($post_id, $meta_key, $meta_value);
                endif;
                // Récupération de l'ordre des metadonnées multiple
                if ($this->isSingle($post_type, $meta_key) === false) :
                    $order[] = $_meta_id;
                endif;
            endforeach;

            // Sauvegarde de l'ordre
            if (! empty($order)) :
                update_post_meta($post_id, '_order_' . $meta_key, $order);
            endif;
        endforeach;

        return;
    }

    /**
     * @inheritdoc
     */
    public function update($post_id, $meta_key, $meta_value)
    {
        if (!$post_type = get_post_type($post_id)) :
            return false;
        endif;

        $unique = $this->isSingle($post_type, $meta_key);

        if ($unique === null):
            return false;
        endif;

        return update_post_meta($post_id, $meta_key, $meta_value, $unique);
    }
}