<?php

namespace tiFy\Contracts\Db;

interface DbFactoryMetaQuery extends DbFactoryResolverTrait
{
    /**
     * Generates SQL clauses to be appended to a main query.
     *
     * @since 3.2.0
     * @access public
     *
     * @param string $type Type of meta, eg 'user', 'post'.
     * @param string $primary_table Database table where the object being filtered is stored (eg wp_users).
     * @param string $primary_id_column ID column for the filtered object in $primary_table.
     * @param object $context Optional. The main query object.
     * @return false|array {
     *     Array containing JOIN and WHERE SQL clauses to append to the main query.
     *
     * @type string $join SQL fragment to append to the main JOIN clause.
     * @type string $where SQL fragment to append to the main WHERE clause.
     * }
     */
    public function get_sql($type, $primary_table, $primary_id_column, $context = null);

    /**
     * Generate SQL JOIN and WHERE clauses for a first-order query clause.
     *
     * "First-order" means that it's an array with a 'key' or 'value'.
     *
     * @since 4.1.0
     * @access public
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param array $clause Query clause, passed by reference.
     * @param array $parent_query Parent query array.
     * @param string $clause_key Optional. The array key used to name the clause in the original `$meta_query`
     *                             parameters. If not provided, a key will be generated automatically.
     * @return array {
     *     Array containing JOIN and WHERE SQL clauses to append to a first-order query.
     *
     * @type string $join SQL fragment to append to the main JOIN clause.
     * @type string $where SQL fragment to append to the main WHERE clause.
     * }
     */
    public function get_sql_for_clause(&$clause, $parent_query, $clause_key = '');
}