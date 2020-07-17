<?php declare(strict_types=1);

namespace tiFy\Taxonomy;

use tiFy\Contracts\Taxonomy\TaxonomyTermMeta as TaxonomyTermMetaContract;
use tiFy\Support\{Str, Proxy\Request};
use WP_Term;

class TaxonomyTermMeta implements TaxonomyTermMetaContract
{
    /**
     * Liste des clés d'indice de metadonnées par taxonomie.
     * @var string[][]
     */
    protected $metaKeys = [];

    /**
     * Liste des clés d'indice de metadonnées à occurrence unique en base de données par taxonomie.
     * @var bool[][]
     */
    protected $singleKeys = [];

    /**
     * Liste des fonctions de rappel des clés d'indice de metadonnées par taxonomie.
     * @var string[]|callable[]
     */
    protected $callbackKeys = [];

    /**
     * @inheritDoc
     */
    public function add(int $id, string $key, $value): ?int
    {
        $term = get_term($id);

        if (!$term instanceof WP_Term) {
            return null;
        } elseif (!$this->exists($term->taxonomy, $key)) {
            return null;
        }

        return add_term_meta($id, $key, $value, $this->isSingle($term->taxonomy, $key)) ?: null;
    }

    /**
     * @inheritDoc
     */
    public function exists(string $tax, string $key): bool
    {
        return in_array($key, $this->metaKeys[$tax] ?? []);
    }

    /**
     * @inheritDoc
     */
    public function get(int $id, string $key): ?array
    {
        global $wpdb;

        $query = "SELECT meta_id, meta_value" . " FROM {$wpdb->termmeta}" . " WHERE 1" .
            " AND {$wpdb->termmeta}.term_id = %d" . " AND {$wpdb->termmeta}.meta_key = %s";

        if ($order = get_term_meta($id, '_order_' . $key, true)) {
            $query .= " ORDER BY FIELD( {$wpdb->termmeta}.term_id," . implode(',', $order) . ")";
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
    public function isSingle(string $tax, string $key): bool
    {
        return isset($this->singleKeys[$tax][$key]) ? $this->singleKeys[$tax][$key] : false;
    }

    /**
     * @inheritDoc
     */
    public function keys(?string $tax = null): array
    {
        return $tax ? ($this->metaKeys[$tax] ?? []) : $this->metaKeys;
    }

    /**
     * @inheritDoc
     */
    public function register(
        string $tax,
        string $key,
        bool $single = false,
        $callback = 'wp_unslash'
    ): TaxonomyTermMetaContract {
        if (empty($this->metaKeys[$tax])) {
            $this->metaKeys[$tax] = [];
        }

        $this->metaKeys[$tax][] = $key;

        if (empty($this->singleKeys[$tax])) {
            $this->singleKeys[$tax] = [];
        }

        if ($single) {
            $this->singleKeys[$tax][$key] = true;
        } else {
            unset($this->singleKeys[$tax][$key]);
        }

        if (!empty($callback) && is_callable($callback)) {
            if (empty($this->callbackKeys[$tax])) {
                $this->callbackKeys[$tax] = [];
            }

            $this->callbackKeys[$tax][$key] = $callback;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function registerSingle(string $tax, string $key, $callback = 'wp_unslash'): TaxonomyTermMetaContract
    {
        return $this->register($tax, $key, true, $callback);
    }

    /**
     * @inheritDoc
     */
    public function registerMulti(string $tax, string $key, $callback = 'wp_unslash'): TaxonomyTermMetaContract
    {
        return $this->register($tax, $key, false, $callback);
    }

    /**
     * @inheritDoc
     */
    public function save(int $term_id, string $taxonomy): void
    {
        if (empty($this->metaKeys[$taxonomy])) {
            return;
        } elseif (!is_admin() || !($screen = get_current_screen()) || !in_array($screen->id, ['edit-tags'])) {
            return;
        }

        // Déclaration des variables
        $meta_keys = $this->metaKeys[$taxonomy];
        $termmeta = [];
        $meta_ids = [];
        $meta_exists = [];
        $request = [];

        // Récupération des metadonnés en $_POST
        foreach ($this->metaKeys[$taxonomy] as $key) {
            if (Request::instance()->has($key)) {
                $request[$key] = Request::post($key);
            }
        }

        foreach ($meta_keys as $meta_key) {
            // Vérification d'existance de la metadonnées en base
            if ($_meta = $this->get($term_id, $meta_key)) {
                $meta_exists += $_meta;
            }

            if (!isset($request[$meta_key])) {
                continue;
            }

            // Récupération des meta_ids de metadonnées unique
            if ($this->isSingle($taxonomy, $meta_key)) {
                $meta_id = $_meta ? key($_meta) : uniqid();
                array_push($meta_ids, $meta_id);
                $termmeta[$meta_key][$meta_id] = $request[$meta_key];

                // Récupération des meta_ids de metadonnées multiple
            } elseif (!$this->isSingle($taxonomy, $meta_key)) {
                $meta_ids += array_keys($request[$meta_key]);
                $termmeta[$meta_key] = $request[$meta_key];
            }
        }

        // Suppression des metadonnées absente du processus de sauvegarde
        foreach ($meta_exists as $meta_id => $meta_value) {
            if (!in_array($meta_id, $meta_ids)) {
                delete_metadata_by_mid('term', $meta_id);
            }
        }

        // Sauvegarde des metadonnées (mise à jour ou ajout)
        foreach ($meta_keys as $meta_key) {
            if (!isset($termmeta[$meta_key])) {
                continue;
            }

            $order = [];
            foreach ((array)$termmeta[$meta_key] as $meta_id => $meta_value) {
                if (isset($this->callbackKeys[$taxonomy][$meta_key])) {
                    $callback = $this->callbackKeys[$taxonomy][$meta_key];

                    $meta_value = $callback($meta_value);
                }

                if (is_int($meta_id) && get_metadata_by_mid('term', $meta_id)) {
                    $_meta_id = $meta_id;
                    update_metadata_by_mid('term', $meta_id, $meta_value);
                } else {
                    $_meta_id = add_term_meta($term_id, $meta_key, $meta_value);
                }

                if (!$this->isSingle($taxonomy, $meta_key)) {
                    $order[] = $_meta_id;
                }
            }

            if (!empty($order)) {
                update_term_meta($term_id, '_order_' . $meta_key, $order);
            }
        }
    }
}