<?php

namespace tiFy\Db\Factory;

use tiFy\Contracts\Db\DbFactory;
use tiFy\Contracts\Db\DbFactoryMetaQuery;
use WP_Meta_Query;

/**
 * Class MetaQuery
 * @package tiFy\Db
 */
class MetaQuery extends WP_Meta_Query implements DbFactoryMetaQuery
{
    use ResolverTrait;

    /**
     * CONSTRUCTEUR.
     *
     * @param DbFactory $db Instance du controleur de base de données associé.
     * @param array $meta_query Paramètres de requête des metadonnées.
     *
     * @return void
     */
    public function __construct(DbFactory $db, $meta_query)
    {
        $this->db = $db;

        parent::__construct($meta_query);
    }

    /**
     * @inheritdoc
     */
    public function get_sql($type, $primary_table, $primary_id_column, $context = null)
    {
        if (!$meta_table = $this->meta()->getTableName()) {
            return false;
        }

        $this->table_aliases = [];

        $this->meta_table = $meta_table;
        $this->meta_id_column = $this->meta()->getJoinCol();

        $this->primary_table = $primary_table;
        $this->primary_id_column = $primary_id_column;

        $sql = $this->get_sql_clauses();

        /*
         * If any JOINs are LEFT JOINs (as in the case of NOT EXISTS), then all JOINs should
         * be LEFT. Otherwise posts with no metadata will be excluded from results.
         */
        if (false !== strpos($sql['join'], 'LEFT JOIN')) {
            $sql['join'] = str_replace('INNER JOIN', 'LEFT JOIN', $sql['join']);
        }

        /**
         * Filters the meta query's generated SQL.
         *
         * @since 3.1.0
         *
         * @param array $clauses Array containing the query's JOIN and WHERE clauses.
         * @param array $queries Array of meta queries.
         * @param string $type Type of meta.
         * @param string $primary_table Primary table.
         * @param string $primary_id_column Primary column ID.
         * @param object $context The main query object.
         */
        return apply_filters_ref_array('get_meta_sql',
            [$sql, $this->queries, $type, $primary_table, $primary_id_column, $context]);
    }

    /**
     * @inheritdoc
     */
    public function get_sql_for_clause(&$clause, $parent_query, $clause_key = '')
    {
        $sql_chunks = [
            'where' => [],
            'join'  => [],
        ];

        if (isset($clause['compare'])) {
            $clause['compare'] = strtoupper($clause['compare']);
        } else {
            $clause['compare'] = isset($clause['value']) && is_array($clause['value']) ? 'IN' : '=';
        }

        if (!in_array($clause['compare'], [
            '=',
            '!=',
            '>',
            '>=',
            '<',
            '<=',
            'LIKE',
            'NOT LIKE',
            'IN',
            'NOT IN',
            'BETWEEN',
            'NOT BETWEEN',
            'EXISTS',
            'NOT EXISTS',
            'REGEXP',
            'NOT REGEXP',
            'RLIKE',
        ])) {
            $clause['compare'] = '=';
        }

        $meta_compare = $clause['compare'];

        // First build the JOIN clause, if one is required.
        $join = '';

        // We prefer to avoid joins if possible. Look for an existing join compatible with this clause.
        $alias = $this->find_compatible_table_alias($clause, $parent_query);
        if (false === $alias) {
            $i = count($this->table_aliases);
            $alias = $i ? 'mt' . $i : $this->meta_table;

            // JOIN clauses for NOT EXISTS have their own syntax.
            if ('NOT EXISTS' === $meta_compare) {
                $join .= " LEFT JOIN $this->meta_table";
                $join .= $i ? " AS $alias" : '';
                $join .= $this->db->sql()->prepare(" ON ($this->primary_table.$this->primary_id_column = $alias.$this->meta_id_column AND $alias.meta_key = %s )",
                    $clause['key']);

                // All other JOIN clauses.
            } else {
                $join .= " INNER JOIN $this->meta_table";
                $join .= $i ? " AS $alias" : '';
                $join .= " ON ( $this->primary_table.$this->primary_id_column = $alias.$this->meta_id_column )";
            }

            $this->table_aliases[] = $alias;
            $sql_chunks['join'][] = $join;
        }

        // Save the alias to this clause, for future siblings to find.
        $clause['alias'] = $alias;

        // Determine the data type.
        $_meta_type = isset($clause['type']) ? $clause['type'] : '';
        $meta_type = $this->get_cast_for_type($_meta_type);
        $clause['cast'] = $meta_type;

        // Fallback for clause keys is the table alias. Key must be a string.
        if (is_int($clause_key) || !$clause_key) {
            $clause_key = $clause['alias'];
        }

        // Ensure unique clause keys, so none are overwritten.
        $iterator = 1;
        $clause_key_base = $clause_key;
        while (isset($this->clauses[$clause_key])) {
            $clause_key = $clause_key_base . '-' . $iterator;
            $iterator++;
        }

        // Store the clause in our flat array.
        $this->clauses[$clause_key] =& $clause;

        // Next, build the WHERE clause.

        // meta_key.
        if (array_key_exists('key', $clause)) {
            if ('NOT EXISTS' === $meta_compare) {
                $sql_chunks['where'][] = $alias . '.' . $this->meta_id_column . ' IS NULL';
            } else {
                $sql_chunks['where'][] = $this->db->sql()->prepare("$alias.meta_key = %s", trim($clause['key']));
            }
        }

        // meta_value.
        if (array_key_exists('value', $clause)) {
            $meta_value = $clause['value'];

            if (in_array($meta_compare, ['IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN'])) {
                if (!is_array($meta_value)) {
                    $meta_value = preg_split('/[,\s]+/', $meta_value);
                }
            } else {
                $meta_value = trim($meta_value);
            }

            switch ($meta_compare) {
                case 'IN' :
                case 'NOT IN' :
                    $meta_compare_string = '(' . substr(str_repeat(',%s', count($meta_value)), 1) . ')';
                    $where = $this->db->sql()->prepare($meta_compare_string, $meta_value);
                    break;

                case 'BETWEEN' :
                case 'NOT BETWEEN' :
                    $meta_value = array_slice($meta_value, 0, 2);
                    $where = $this->db->sql()->prepare('%s AND %s', $meta_value);
                    break;

                case 'LIKE' :
                case 'NOT LIKE' :
                    $meta_value = '%' . $this->db->sql()->esc_like($meta_value) . '%';
                    $where = $this->db->sql()->prepare('%s', $meta_value);
                    break;

                // EXISTS with a value is interpreted as '='.
                case 'EXISTS' :
                    $meta_compare = '=';
                    $where = $this->db->sql()->prepare('%s', $meta_value);
                    break;

                // 'value' is ignored for NOT EXISTS.
                case 'NOT EXISTS' :
                    $where = '';
                    break;

                default :
                    $where = $this->db->sql()->prepare('%s', $meta_value);
                    break;

            }

            if ($where) {
                if ('CHAR' === $meta_type) {
                    $sql_chunks['where'][] = "$alias.meta_value {$meta_compare} {$where}";
                } else {
                    $sql_chunks['where'][] = "CAST($alias.meta_value AS {$meta_type}) {$meta_compare} {$where}";
                }
            }
        }

        /*
         * Multiple WHERE clauses (for meta_key and meta_value) should
         * be joined in parentheses.
         */
        if (1 < count($sql_chunks['where'])) {
            $sql_chunks['where'] = ['( ' . implode(' AND ', $sql_chunks['where']) . ' )'];
        }

        return $sql_chunks;
    }
}