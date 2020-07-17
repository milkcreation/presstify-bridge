<?php declare(strict_types=1);

namespace tiFy\User;

use tiFy\Contracts\User\UserMeta as UserMetaContract;
use tiFy\Support\{Arr, Str, Proxy\Request};

class UserMeta implements UserMetaContract
{
    /**
     * Liste des clés d'indice de metadonnées.
     * @var string[]
     */
    protected $metaKeys = [];

    /**
     * Liste des métadonnées à occurrence unique en base de données.
     * @var bool[]
     */
    protected $singleKeys = [];

    /**
     * Liste des fonctions de rappel.
     * @var string[]|callable[]
     */
    protected $callbackKeys = [];

    /**
     * @inheritDoc
     */
    public function add(int $id, string $key, $value): ?int
    {
        if (!$this->exists($key)) {
            return null;
        }

        return add_user_meta($id, $key, $value, $this->isSingle($key)) ?: null;
    }

    /**
     * @inheritDoc
     */
    public function exists(string $key): bool
    {
        return in_array($key, $this->metaKeys);
    }

    /**
     * @inheritDoc
     */
    public function get(int $id, string $key): ?array
    {
        global $wpdb;

        $query = "SELECT umeta_id as meta_id, meta_value" . " FROM {$wpdb->usermeta}" . " WHERE 1" .
            " AND {$wpdb->usermeta}.user_id = %d" . " AND {$wpdb->usermeta}.meta_key = %s";

        if ($order = get_user_meta($id, '_order_' . $key, true)) {
            $query .= " ORDER BY FIELD( {$wpdb->usermeta}.user_id," . implode(',', $order) . ")";
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
    public function isSingle(string $key): bool
    {
        return $this->singleKeys[$key] ?? false;
    }

    /**
     * @inheritDoc
     */
    public function keys(): array
    {
        return $this->metaKeys ?: [];
    }

    /**
     * @inheritDoc
     */
    public function register(string $key, bool $single = false, $callback = 'wp_unslash'): UserMetaContract
    {
        $this->metaKeys[] = $key;

        if ($single) {
            $this->singleKeys[$key] = true;
        } else {
            unset($this->singleKeys[$key]);
        }

        if (!empty($callback) && is_callable($callback)) {
            $this->callbackKeys[$key] = $callback;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function registerSingle(string $key, $callback = 'wp_unslash'): UserMetaContract
    {
        return $this->register($key, true, $callback);
    }

    /**
     * @inheritDoc
     */
    public function registerMulti(string $key, $callback = 'wp_unslash'): UserMetaContract
    {
        return $this->register($key, false, $callback);
    }

    /**
     * @inheritDoc
     */
    public function save(int $user_id): void
    {
        if (empty($this->metaKeys)) {
            return;
        } elseif (!is_admin() || !($screen = get_current_screen()) || !in_array($screen->id, ['profile', 'user-edit'])) {
            return;
        }

        $exists = [];
        $ids = [];
        $proceed = [];
        $request = [];

        foreach ($this->metaKeys as $key) {
            if (Request::instance()->has($key)) {
                $request[$key] = Request::post($key);
            }
        }

        foreach ($this->metaKeys as $key) {
            if ($exist = $this->get($user_id, $key)) {
                $exists += $exist;
            }

            if (isset($request[$key])) {
                if ($this->isSingle($key)) {
                    $id = $exist ? key($exist) : uniqid();

                    array_push($ids, $id);

                    $proceed[$key][$id] = $request[$key];
                } else {
                    $values = Arr::wrap($request[$key]);
                    $keys = array_map('intval', array_keys($values));

                    foreach ($keys as $k) {
                        $id = $exist[$k] ?: uniqid();
                        array_push($ids, $id);
                    }

                    $proceed[$key] = $values;
                }
            }
        }

        foreach (array_keys($exists) as $id) {
            if (!in_array($id, $ids)) {
                delete_metadata_by_mid('user', $id);
            }
        }

        foreach ($this->metaKeys as $key) {
            if (isset($proceed[$key])) {
                $order = [];

                foreach ($proceed[$key] as $id => $value) {
                    if ($callback = $this->callbackKeys[$key] ?? null) {
                        $value = $callback($value);
                    }

                    if (is_int($id) && get_metadata_by_mid('user', $id)) {
                        $meta_id = $id;

                        update_metadata_by_mid('user', $meta_id, $value);
                    } elseif ($added = add_user_meta($user_id, $key, $value)) {
                        $meta_id = $added;
                    }

                    if (!$this->isSingle($key) && isset($meta_id)) {
                        $order[] = $meta_id;
                    }
                }

                if (!empty($order)) {
                    update_user_meta($user_id, '_order_' . $key, $order);
                }
            }
        }
    }
}