<?php

namespace tiFy\Db\Factory;

use tiFy\Contracts\Db\DbFactory;
use tiFy\Contracts\Db\DbFactoryMeta;

class Meta implements DbFactoryMeta
{
    use ResolverTrait;

    /**
     * CONSTRUCTEUR.
     *
     * @param DbFactory $db Instance du controleur de base de données associé.
     *
     * @return void
     */
    public function __construct(DbFactory $db)
    {
        $this->db = $db;
    }

    /**
     *
     * @param string $type
     *
     * @return string
     *
     * @todo
     */
    private function _get_meta_table($type)
    {
        $table_name = $type . 'meta';

        if (! empty($this->db->sql()->{$table_name})) :
            return $this->db->sql()->{$table_name};
        endif;

        return '';
    }

    /**
     *
     * @param array $object_ids
     *
     * @return array
     *
     * @todo
     */
    private function _update_meta_cache($object_ids)
    {
        if (!($meta_type = $this->db->getMetaType())|| !$object_ids) :
            return [];
        endif;

        $table = $this->getTableName();
        if (!$table) :
            return [];
        endif;

        $column = $this->getJoinCol();

        if (!is_array($object_ids)) {
            $object_ids = preg_replace('|[^0-9,]|', '', $object_ids);
            $object_ids = explode(',', $object_ids);
        }

        $object_ids = array_map('intval', $object_ids);

        $cache_key = "{$meta_type}_meta";
        $ids = [];
        $cache = [];
        foreach ($object_ids as $id) {
            $cached_object = wp_cache_get($id, $cache_key);
            if (false === $cached_object) {
                $ids[] = $id;
            } else {
                $cache[$id] = $cached_object;
            }
        }

        if (empty($ids)) {
            return $cache;
        }

        // Get meta info
        $id_list = join(',', $ids);
        $id_column = ('user' === $meta_type) ? 'umeta_id' : 'meta_id';
        $meta_list = $this->db->sql()->get_results(
            "SELECT $column, meta_key, meta_value FROM $table WHERE $column IN ($id_list) ORDER BY $id_column ASC",
            ARRAY_A
        );

        if (!empty($meta_list)) {
            foreach ($meta_list as $metarow) {
                $mpid = intval($metarow[$column]);
                $mkey = $metarow['meta_key'];
                $mval = $metarow['meta_value'];

                // Force subkeys to be array type:
                if (!isset($cache[$mpid]) || !is_array($cache[$mpid])) {
                    $cache[$mpid] = [];
                }
                if (!isset($cache[$mpid][$mkey]) || !is_array($cache[$mpid][$mkey])) {
                    $cache[$mpid][$mkey] = [];
                }

                // Add a value to the current pid/key:
                $cache[$mpid][$mkey][] = $mval;
            }
        }

        foreach ($ids as $id) {
            if (!isset($cache[$id])) {
                $cache[$id] = [];
            }
            wp_cache_add($id, $cache[$id], $cache_key);
        }

        return $cache;
    }

    /**
     * @inheritdoc
     */
    public function add($object_id, $meta_key, $meta_value, $unique = true)
    {
        if (!($meta_type = $this->db->getMetaType()) || !$meta_key || !is_numeric($object_id)) :
            return false;
        endif;

        $object_id = absint($object_id);
        if (!$object_id) :
            return false;
        endif;

        $table = $this->getTableName();
        if (!$table) :
            return false;
        endif;

        $column = $this->getJoinCol();

        $meta_key = \wp_unslash($meta_key);
        $meta_value = \wp_unslash($meta_value);
        $meta_value = sanitize_meta($meta_key, $meta_value, $meta_type);

        $check = apply_filters("add_{$meta_type}_metadata", null, $object_id, $meta_key, $meta_value, $unique);
        if (null !== $check) :
            return $check;
        endif;

        if (
            $unique &&
            $this->db->sql()->get_var(
                $this->db->sql()->prepare(
                    "SELECT COUNT(*) FROM $table WHERE meta_key = %s AND $column = %d",
                    $meta_key,
                    $object_id
                )
            )
        ) :
            return false;
        endif;

        $_meta_value = $meta_value;
        $meta_value = maybe_serialize($meta_value);

        do_action("add_{$meta_type}_meta", $object_id, $meta_key, $_meta_value);

        $result = $this->db->sql()->insert(
            $table,
            [
                $column      => $object_id,
                'meta_key'   => $meta_key,
                'meta_value' => $meta_value,
            ]
        );

        if (!$result) :
            return false;
        endif;

        $mid = (int)$this->db->sql()->insert_id;

        wp_cache_delete($object_id, "{$meta_type}_meta");

        do_action("added_{$meta_type}_meta", $mid, $object_id, $meta_key, $_meta_value);

        return $mid;
    }

    /**
     * @inheritdoc
     */
    public function all($object_id)
    {
        return $this->get($object_id);
    }

    /**
     * @inheritdoc
     */
    public function delete($object_id, $meta_key, $meta_value = '', $delete_all = false)
    {
        if (!($meta_type = $this->db->getMetaType()) || !$meta_key || !is_numeric($object_id) && !$delete_all) :
            return false;
        endif;

        $object_id = absint($object_id);
        if (!$object_id && !$delete_all) :
            return false;
        endif;

        $table = $this->getTableName();
        if (!$table) {
            return false;
        }

        $type_column = $this->getJoinCol();
        $id_column = ('user' === $meta_type) ? 'umeta_id' : 'meta_id';

        $meta_key = wp_unslash($meta_key);
        $meta_value = wp_unslash($meta_value);

        $check = apply_filters(
            "delete_{$meta_type}_metadata",
            null,
            $object_id,
            $meta_key,
            $meta_value,
            $delete_all
        );
        if (null !== $check) :
            return (bool)$check;
        endif;

        $_meta_value = $meta_value;
        $meta_value = maybe_serialize($meta_value);

        $query = $this->db->sql()->prepare("SELECT $id_column FROM $table WHERE meta_key = %s", $meta_key);

        if (!$delete_all) :
            $query .= $this->db->sql()->prepare(" AND $type_column = %d", $object_id);
        endif;

        if ('' !== $meta_value && null !== $meta_value && false !== $meta_value) :
            $query .= $this->db->sql()->prepare(" AND meta_value = %s", $meta_value);
        endif;

        $meta_ids = $this->db->sql()->get_col($query);
        if (!count($meta_ids)) :
            return false;
        endif;

        $object_ids = [];

        if ($delete_all) :
            $value_clause = '';
            if ('' !== $meta_value && null !== $meta_value && false !== $meta_value) :
                $value_clause = $this->db->sql()->prepare(" AND meta_value = %s", $meta_value);
            endif;

            $object_ids = $this->db->sql()->get_col(
                $this->db->sql()->prepare(
                    "SELECT $type_column FROM $table WHERE meta_key = %s $value_clause",
                    $meta_key
                )
            );
        endif;

        do_action("delete_{$meta_type}_meta", $meta_ids, $object_id, $meta_key, $_meta_value);

        if ('post' == $meta_type) :
            do_action('delete_postmeta', $meta_ids);
        endif;

        $query = "DELETE FROM $table WHERE $id_column IN( " . implode(',', $meta_ids) . " )";

        $count = $this->db->sql()->query($query);

        if (!$count) :
            return false;
        endif;

        if ($delete_all) :
            if ($object_ids) :
                foreach ((array)$object_ids as $o_id) :
                    wp_cache_delete($o_id, "{$meta_type}_meta");
                endforeach;
            endif;
        else :
            wp_cache_delete($object_id, "{$meta_type}_meta");
        endif;

        do_action("deleted_{$meta_type}_meta", $meta_ids, $object_id, $meta_key, $_meta_value);

        if ('post' == $meta_type) :
            do_action('deleted_postmeta', $meta_ids);
        endif;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteAll($id)
    {
        $table = $this->getTableName();
        if (!$table) :
            return null;
        endif;
        $column = $this->getJoinCol();

        $this->db->sql()->delete($table, [$column => $id], '%d');
    }

    /**
     * @inheritdoc
     */
    public function get($object_id, $meta_key = '', $single = true)
    {
        if (!($meta_type = $this->db->getMetaType()) || !is_numeric($object_id)) :
            return false;
        endif;

        $object_id = absint($object_id);
        if (!$object_id) :
            return false;
        endif;

        $check = apply_filters("get_{$meta_type}_metadata", null, $object_id, $meta_key, $single);
        if (null !== $check) :
            if ($single && is_array($check)) :
                return $check[0];
            else :
                return $check;
            endif;
        endif;

        if (!$meta_cache = wp_cache_get($object_id, "{$meta_type}_meta")) :
            $meta_cache = $this->_update_meta_cache([$object_id]);
            $meta_cache = $meta_cache[$object_id];
        endif;

        if (!$meta_key) :
            return $meta_cache;
        endif;

        if (isset($meta_cache[$meta_key])) :
            if ($single) :
                return maybe_unserialize($meta_cache[$meta_key][0]);
            else :
                return array_map('maybe_unserialize', $meta_cache[$meta_key]);
            endif;
        endif;

        if ($single) :
            return '';
        else :
            return [];
        endif;
    }

    /**
     * @inheritdoc
     */
    public function getByMid($meta_id)
    {
        if (!($meta_type = $this->db->getMetaType()) || !is_numeric($meta_id)) :
            return false;
        endif;

        $meta_id = absint($meta_id);
        if (!$meta_id) :
            return false;
        endif;

        $table = $this->getTableName();
        if (!$table) :
            return false;
        endif;

        $id_column = ('user' === $meta_type) ? 'umeta_id' : 'meta_id';

        $meta = $this->db->sql()->get_row(
            $this->db->sql()->prepare(
                "SELECT * FROM $table WHERE $id_column = %d",
                $meta_id
            )
        );

        if (empty($meta)) :
            return false;
        endif;

        if (isset($meta->meta_value)) :
            $meta->meta_value = maybe_unserialize($meta->meta_value);
        endif;

        return $meta;
    }

    /**
     * @inheritdoc
     */
    public function getJoinCol()
    {
        return $this->db->getMetaJoinCol() ?: sanitize_key($this->db->getMetaType() . '_id');
    }

    /**
     * @inheritdoc
     */
    public function getPrimary()
    {
        return 'user' == $this->db->getMetaType() ? 'umeta_id' : 'meta_id';
    }

    /**
     * @inheritdoc
     */
    public function getTableName()
    {
        return $this->_get_meta_table($this->db->getMetaType());
    }

    /**
     * @inheritdoc
     */
    public function update($object_id, $meta_key, $meta_value, $prev_value = '')
    {
        if (!($meta_type = $this->db->getMetaType()) || !$meta_key || !is_numeric($object_id)) :
            return false;
        endif;

        $object_id = absint($object_id);
        if (!$object_id) :
            return false;
        endif;

        $table = $this->getTableName();
        if (!$table) :
            return false;
        endif;

        $column = $this->getJoinCol();
        $id_column = ('user' === $meta_type) ? 'umeta_id' : 'meta_id';

        $raw_meta_key = $meta_key;
        $meta_key = wp_unslash($meta_key);
        $passed_value = $meta_value;
        $meta_value = wp_unslash($meta_value);
        $meta_value = sanitize_meta($meta_key, $meta_value, $meta_type);

        $check = apply_filters("update_{$meta_type}_metadata", null, $object_id, $meta_key, $meta_value,
            $prev_value);
        if (null !== $check) :
            return (bool)$check;
        endif;

        if (empty($prev_value)) :
            $old_value = $this->get($object_id, $meta_key, false);
            if (count($old_value) == 1) :
                if ($old_value[0] === $meta_value) :
                    return false;
                endif;
            endif;
        endif;

        $meta_ids = $this->db->sql()->get_col(
            $this->db->sql()->prepare(
                "SELECT $id_column FROM $table WHERE meta_key = %s AND $column = %d",
                $meta_key,
                $object_id
            )
        );
        if (empty($meta_ids)) :
            return $this->add($object_id, $raw_meta_key, $passed_value);
        endif;

        $_meta_value = $meta_value;
        $meta_value = maybe_serialize($meta_value);

        $data = compact('meta_value');
        $where = [$column => $object_id, 'meta_key' => $meta_key];

        if (!empty($prev_value)) :
            $prev_value = maybe_serialize($prev_value);
            $where['meta_value'] = $prev_value;
        endif;

        foreach ($meta_ids as $meta_id) :
            do_action("update_{$meta_type}_meta", $meta_id, $object_id, $meta_key, $_meta_value);

            if ('post' == $meta_type) :
                do_action('update_postmeta', $meta_id, $object_id, $meta_key, $meta_value);
            endif;
        endforeach;

        $result = $this->db->sql()->update($table, $data, $where);
        if (!$result) :
            return false;
        endif;

        wp_cache_delete($object_id, "{$meta_type}_meta");

        foreach ($meta_ids as $meta_id) :
            do_action("updated_{$meta_type}_meta", $meta_id, $object_id, $meta_key, $_meta_value);

            if ('post' == $meta_type) :
                do_action('updated_postmeta', $meta_id, $object_id, $meta_key, $meta_value);
            endif;
        endforeach;

        return true;
    }
}
