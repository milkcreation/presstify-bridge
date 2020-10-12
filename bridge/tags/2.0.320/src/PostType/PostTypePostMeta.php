<?php declare(strict_types=1);

namespace tiFy\PostType;

use tiFy\Contracts\PostType\PostTypePostMeta as PostTypePostMetaContract;
use tiFy\Support\{Str, Proxy\Request};

class PostTypePostMeta implements PostTypePostMetaContract
{
    /**
     * Liste des clés d'indice de metadonnées par type de post.
     * @var string[][]
     */
    protected $metaKeys = [];

    /**
     * Liste des clés d'indice de metadonnées à occurrence unique en base de données par type de post.
     * @var bool[][]
     */
    protected $singleKeys = [];

    /**
     * Liste des fonctions de rappel des clés d'indice de metadonnées par type de post.
     * @var string[]|callable[]
     */
    protected $callbackKeys = [];

    /**
     * @inheritDoc
     */
    public function add(int $id, string $key, $value): ?int
    {
        if (!$type = get_post_type($id)) {
            return null;
        } elseif (!$this->exists($type, $key)) {
            return null;
        }

        return add_post_meta($id, $key, $value, $this->isSingle($type, $key)) ?: null;
    }

    /**
     * @inheritDoc
     */
    public function exists(string $type, string $key): bool
    {
        return in_array($key, $this->metaKeys[$type] ?? []);
    }

    /**
     * @inheritDoc
     */
    public function get(int $id, string $key): ?array
    {
        global $wpdb;

        $query = "SELECT meta_id, meta_value" . " FROM {$wpdb->postmeta}" .
            " WHERE 1" . " AND {$wpdb->postmeta}.post_id = %d" . " AND {$wpdb->postmeta}.meta_key = %s";

        if ($order = get_post_meta($id, '_order_' . $key, true)) {
            $query .= " ORDER BY FIELD( {$wpdb->postmeta}.meta_id," . implode(',', $order) . ")";
        }

        if (!$res = $wpdb->get_results($wpdb->prepare($query, $id, $key))) {
            return null;
        } else {
            $metas = [];

            foreach ((array)$res as $args) {
                $metas[$args->meta_id] = Str::unserialize($args->meta_value);
            }

            return $metas;
        }
    }

    /**
     * @inheritDoc
     */
    public function isSingle(string $type, string $key): bool
    {
        return isset($this->singleKeys[$type][$key]) ? $this->singleKeys[$type][$key] : false;
    }

    /**
     * @inheritDoc
     */
    public function keys(?string $type = null): array
    {
        return $type ? ($this->metaKeys[$type] ?? []) : $this->metaKeys;
    }

    /**
     * @inheritDoc
     */
    public function register(
        string $type,
        string $key,
        bool $single = false,
        $callback = 'wp_unslash'
    ): PostTypePostMetaContract {
        if (empty($this->metaKeys[$type])) {
            $this->metaKeys[$type] = [];
        }

        $this->metaKeys[$type][] = $key;

        if (empty($this->singleKeys[$type])) {
            $this->singleKeys[$type] = [];
        }

        if ($single) {
            $this->singleKeys[$type][$key] = true;
        } else {
            unset($this->singleKeys[$type][$key]);
        }

        if (!empty($callback) && is_callable($callback)) {
            if (empty($this->callbackKeys[$type])) {
                $this->callbackKeys[$type] = [];
            }

            $this->callbackKeys[$type][$key] = $callback;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function registerSingle(string $type, string $key, $callback = 'wp_unslash'): PostTypePostMetaContract
    {
        return $this->register($type, $key, true, $callback);
    }

    /**
     * @inheritDoc
     */
    public function registerMulti(string $type, string $key, $callback = 'wp_unslash'): PostTypePostMetaContract
    {
        return $this->register($type, $key, false, $callback);
    }

    /**
     * @inheritDoc
     */
    public function save(int $post_id, string $post_type): void
    {
        if (empty($this->metaKeys[$post_type])) {
            return;
        } elseif (!is_admin() || !($screen = get_current_screen()) || !in_array($screen->id, [$post_type])) {
            return;
        }

        $meta_keys = $this->metaKeys[$post_type];
        $postmeta = [];
        $meta_ids = [];
        $meta_exists = [];
        $request = [];

        foreach ($meta_keys as $key) {
            if (Request::instance()->has($key)) {
                $request[$key] = Request::post($key);
            }
        }

        foreach ($meta_keys as $meta_key) {
            // Vérification d'existance de la metadonnées en base
            if ($_meta = $this->get($post_id, $meta_key)) {
                $meta_exists += $_meta;
            }

            if (!isset($request[$meta_key])) {
                continue;
            }

            // Récupération des meta_ids de metadonnées unique
            if ($this->isSingle($post_type, $meta_key)) {
                $meta_id = $_meta ? key($_meta) : uniqid();
                array_push($meta_ids, $meta_id);
                $postmeta[$meta_key][$meta_id] = $request[$meta_key];

                // Récupération des meta_ids de metadonnées multiple
            } elseif ($this->isSingle($post_type, $meta_key) === false) {
                $meta_ids += array_keys($request[$meta_key]);
                $postmeta[$meta_key] = $request[$meta_key];
            }
        }

        // Suppression des metadonnées absente du processus de sauvegarde
        foreach ($meta_exists as $meta_id => $meta_value) {
            if (!in_array($meta_id, $meta_ids)) {
                delete_metadata_by_mid('post', $meta_id);
            }
        }

        // Sauvegarde des metadonnées (mise à jour ou ajout)
        foreach ($meta_keys as $meta_key) {
            if (!isset($postmeta[$meta_key])) {
                continue;
            }

            $order = [];
            foreach ((array)$postmeta[$meta_key] as $meta_id => $meta_value) {
                if (isset($this->callbackKeys[$post_type][$meta_key])) {
                    $callback = $this->callbackKeys[$post_type][$meta_key];

                    $meta_value = $callback($meta_value);
                }

                if (is_int($meta_id) && get_post_meta_by_id($meta_id)) {
                    $_meta_id = $meta_id;
                    update_metadata_by_mid('post', $meta_id, $meta_value);
                } else {
                    $_meta_id = add_post_meta($post_id, $meta_key, $meta_value);
                }

                if (!$this->isSingle($post_type, $meta_key)) {
                    $order[] = $_meta_id;
                }
            }

            if (!empty($order)) {
                update_post_meta($post_id, '_order_' . $meta_key, $order);
            }
        }
    }
}