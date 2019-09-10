<?php

namespace tiFy\Db\Factory;

use tiFy\Contracts\Db\DbFactory;
use tiFy\Contracts\Db\DbFactoryMetaQuery;
use tiFy\Contracts\Db\DbFactoryParser;

class Parser implements DbFactoryParser
{
    use ResolverTrait;

    /**
     * @var DbFactoryMetaQuery
     */
    protected $metaQuery;

    /**
     * @var array
     */
    protected $metaClauses = [];

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
     * Traitement de la requête SQL
     *
     * @param array $query_vars Liste des arguments de requête
     *
     * @return string
     */
    public function query($query_vars = [])
    {
        // Traitement des arguments de requête
        $query_vars = $this->query_vars($query_vars);

        // SELECT
        if (! $Select = $this->clause_select($query_vars['fields'])) :
            return '';
        endif;

        // FROM
        $From = $this->clause_from();

        // JOIN
        $Join = $this->clause_join();

        // WHERE
        $Where = $this->clause_where($query_vars);

        // WHERE dans le cadre d'une recherche
        if ($query_vars['s']) :
            $Where .= $this->clause_search($query_vars['s']);
        endif;

        // WHERE dans le cadre d'une inclusion d'éléments
        if ($query_vars['item__in']) :
            $Where .= $this->clause__in($query_vars['item__in']);
        endif;

        // WHERE dans le cadre d'une exclusion d'éléments
        if ($query_vars['item__not_in']) :
            $Where .= $this->clause__not_in($query_vars['item__not_in']);
        endif;

        // GROUPBY
        $GroupBy = $this->clause_group_by();


        // ORDER
        /*
        if ($query_vars['item__in'] && ($args['orderby'] === 'item__in'))
            $query .= " ORDER BY FIELD({$this->db->Name}.{$this->db->Primary}, {$query_vars['item__in']})";
        else
        */
        $OrderBy = $this->clause_order($query_vars['orderby'], $query_vars['order']);

        // LIMIT
        $Limit = $this->clause_limit($query_vars['per_page'], $query_vars['paged']);

        return "{$Select}{$From}{$Join}{$Where}{$GroupBy}{$OrderBy}{$Limit}";
    }

    /**
     * Traitements des arguments de requête.
     *
     * @param array $vars
     * @param mixed $defaults
     *
     * @return array
     */
    public function query_vars($vars, $defaults = null)
    {
        if (is_null($defaults)) :
            $defaults = [
                'item__in'          => '',
                'item__not_in'      => '',
                's'                 => '',
                'fields'            => '',
                'meta_query'        => [],
                'per_page'          => -1,
                'paged'             => 1,
                'order'             => 'DESC',
                'orderby'           => $this->db->getPrimary()
            ];
        endif;
        $vars = \wp_parse_args($vars, $defaults);
        
        // Gestion des requêtes de métadonnées
        if (!empty($vars['meta_query']) && $this->db->hasMeta()) :
            $this->metaQuery = $this->meta_query($vars['meta_query']);

            $this->metaClauses = $this->metaQuery->get_sql(
                $this->db->getMetaType(),
                $this->db->getTableName(),
                $this->db->getPrimary(),
                null
            );
        endif;

        // Retro-Compatibilité
        if (!empty($vars['include'])) :
            $vars['item__in'] = $vars['include'];
            unset($vars['include']);
        endif;

        if (!empty($vars['exclude'])) :
            $vars['item__not_in'] = $vars['exclude'];
            unset($vars['exclude']);
        endif;

        return $vars;
    }

    /**
     * Récupération de la condition SELECT
     *
     * @param string[] $fields
     *
     * @return string
     */
    public function clause_select($fields = null)
    {
        $table_name = $this->db->getTableName();

        if (!$fields) :
            return "SELECT {$table_name}.*";
        elseif (is_string($fields)) :
            if ($col_name = $this->db->existsCol($fields)) :
                return "SELECT {$table_name}.{$col_name}";
            else:
                return "SELECT $fields";
            endif;
        elseif (is_array($fields)) :
            $_fields = [];
            foreach ($fields as $field) :
                if (!$col_name = $this->db->existsCol($field)) :
                    return '';
                endif;
                $_fields[] = "{$table_name}.{$col_name}";
            endforeach;
            return "SELECT " . implode(', ', $_fields);
        else :
            return "SELECT *";
        endif;
    }

    /**
     * Récupération de la condition FROM
     *
     * @return string
     */
    public function clause_from()
    {
        return " FROM " . $this->db->getTableName();
    }

    /**
     * Traitement de la condition JOIN
     *
     * @return string
     */
    public function clause_join()
    {
        $join = [];
        
        // Traitement des conditions relatives au metadonnées
        if (!empty($this->metaClauses['join'])) :
            $join[] = trim($this->metaClauses['join']);
        endif;
        
        if (!empty( $join )) :
            return " " . implode(' ', $join);
        endif;

        return '';
    }

    /**
     * Traitement de la condition WHERE
     *
     * @param array $vars
     *
     * @return string
     */
    public function clause_where($vars)
    {
        $where = [];
        $output = " WHERE 1";
        
        // Traitement des conditions relatives aux colonnes de la table principale
        if ($cols = $this->validate($vars)) :
            $table_name = $this->db->getTableName();

            foreach ((array)$cols as $col_name => $value) :
                if (is_string($value)) :
                    $where[] = "AND {$table_name}.{$col_name} = '{$value}'";
                elseif (is_bool($value) && $value) :
                    $where[] = "AND {$table_name}.{$col_name}";
                elseif (is_bool($value) &&  !$value) :
                    $where[] = "AND ! {$table_name}.{$col_name}";
                elseif (is_numeric($value)) :
                    $where[] = "AND {$table_name}.{$col_name} = {$value}";
                elseif (is_array($value)) :
                    $where[] = "AND {$table_name}.{$col_name} ". $this->clause_where_compare_value($value);
                elseif (is_null($value)) :
                    $where[] = "AND {$table_name}.{$col_name} IS NULL";
                endif;
            endforeach;
        endif;
        
        // Traitement des conditions relatives au metadonnées
        if (!empty($this->metaClauses['where'])) :
            $where[] = trim( $this->metaClauses['where']);
        endif;

        if ($where) :
            $output .= " ". implode(' ', $where);
        endif;

        return $output;
    }
    
    /**
     * Traitement des comparaison de valeur la condition WHERE
     *
     * @param array $col_value
     *
     * @return string
     */
    public function clause_where_compare_value($col_value)
    {
        if (array_key_exists('value', $col_value)) :
            $value   = $col_value['value'];
            $compare = isset($col_value['compare']) ? $col_value['compare'] : '';

            if (in_array($compare, ['IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN'])) :
                if (!is_array($value)) :
                    $value = preg_split('#[,\s]+#', $value);
                endif;
            else :
                $value = trim($value);
            endif;

            switch ($compare) :
                case 'IN' :
                case 'NOT IN' :
                    $compare_string = '(' . substr(str_repeat(', %s', count($value)), 1) . ')';
                    $where = $this->db->sql()->prepare($compare_string, $value);
                    break;

                case 'BETWEEN' :
                case 'NOT BETWEEN' :
                    $value = array_slice($value, 0, 2);
                    $where = $this->db->sql()->prepare('%s AND %s', $value);
                    break;

                case 'LIKE' :
                case 'NOT LIKE' :
                    $mvalue = '%' . $this->db->sql()->esc_like($value) . '%';
                    $where  = $this->db->sql()->prepare('%s', $mvalue);
                    break;

                case 'EXISTS' :
                    $compare = '=';
                    $where   = $this->db->sql()->prepare('%s', $value);
                    break;

                case 'NOT EXISTS' :
                    $where = '';
                    break;

                default :
                    $where = $this->db->sql()->prepare('%s', $value);
                    break;
            endswitch;
        else :
            $compare = 'IN';
            $where   = "('" . implode("', '", $col_value) . "')";
        endif;

        return "{$compare} {$where}";
    }

    /**
     * Traitement de la condition WHERE dans le cadre d'une recherche
     *
     * @param string $terms
     *
     * @return string
     */
    public function clause_search($terms = '')
    {
        if (empty($terms) || ! $this->db->hasSearch()) :
            return null;
        endif;

        $like = '%' . $this->db->sql()->esc_like($terms) . '%';

        $search_query = [];
        foreach ($this->db->getSearchColumns() as $col_name) :
            $search_query[] = $this->db->getTableName() . ".{$col_name} LIKE '{$like}'";
        endforeach;

        if ($search_query) :
            return " AND (" . join(" OR ", $search_query) . ")";
        endif;

        return '';
    }

    /**
     * Traitement de la condition WHERE dans le cadre d'une inclusion d'éléments
     *
     * @param int[] $ids
     *
     * @return string
     */
    public function clause__in($ids)
    {
        // Bypass
        if (!$ids) :
            return '';
        endif;

        if (!is_array($ids)) :
            $ids = [$ids];
        endif;
        $__in = implode(',', array_map('absint', $ids));

        return " AND " . $this->db->getTableName() . "." . $this->db->getPrimary() . " IN ({$__in})";
    }

    /**
     * Traitement de la condition WHERE dans le cadre d'une exclusion d'éléments
     *
     * @param int[] $ids
     *
     * @return string
     */
    public function clause__not_in($ids)
    {
        // Bypass
        if (!$ids) :
            return '';
        endif;

        if (!is_array($ids)) :
            $ids = [$ids];
        endif;

        $__not_in = implode(',', array_map('absint', $ids));

        return " AND " . $this->db->getTableName() . "." . $this->db->getPrimary() . "  NOT IN ({$__not_in})";
    }

    /**
     * Traitement de la condition GROUPBY
     *
     * @return string
     */
    public function clause_group_by()
    {
        if ($this->metaClauses) :
            return " GROUP BY " . $this->db->getTableName() . "." . $this->db->getPrimary();
        endif;

        return '';
    }

    /**
     * Traitement de la condition ORDER.
     *
     * @param string|array $orderby
     * @param string $order
     *
     * @return string
     */
    public function clause_order($orderby, $order = 'DESC')
    {
        $orderby_array = [];

        if (is_array($orderby)) :
            foreach ($orderby as $_orderby => $order) :
                $orderby = addslashes_gpc(urldecode($_orderby));
                $parsed  = $this->parse_orderby($orderby);

                if (!$parsed) :
                    continue;
                endif;

                $orderby_array[] = $parsed . ' ' . $this->parse_order($order);
            endforeach;

            $orderby = implode(', ', $orderby_array);
        else :
            $orderby = urldecode($orderby);
            $orderby = addslashes_gpc($orderby);

            foreach (explode(' ', $orderby) as $i => $orderby) :
                $parsed = $this->parse_orderby($orderby);
                if (!$parsed) :
                    continue;
                endif;

                $orderby_array[] = $parsed;
            endforeach;

            $orderby = implode(" {$order},", $orderby_array);

            if (empty($orderby)) :
                $orderby = $this->db->getTableName() . "." . $this->db->getPrimary() . " {$order}";
            elseif (!empty($order)) :
                $orderby .= " {$order}";
            endif;
        endif;

        return " ORDER BY {$orderby}";
    }
    
    /**
     * Traitement du champs d'ordonnacement
     *
     * @param string $orderby
     *
     * @return string
     */
    public function parse_orderby($orderby)
    {
        if (($orderby === 'meta_value') && $this->metaQuery) :
            $clauses = $this->metaQuery->get_clauses();
            $primary_meta_query = reset($clauses);
            $orderby_clause = "CAST({$primary_meta_query['alias']}.meta_value AS {$primary_meta_query['cast']})";
        elseif ($orderby = $this->db->existsCol($orderby)) :
            $orderby_clause = $this->db->getTableName() . ".{$orderby}";
        else :
            $orderby_clause = $this->db->getTableName() . "." . $this->db->getPrimary();
        endif;

        return $orderby_clause;
    }
    
    /**
     * Traitement du facteur d'ordonnacement
     *
     * @param string $order ASC|DESC
     *
     * @return string
     */
    public function parse_order($order = null)
    {
        if (!is_string($order) || empty($order)) :
            return 'DESC';
        endif;

        if ('ASC' === strtoupper($order)) :
            return 'ASC';
        else :
            return 'DESC';
        endif;
    }

    /**
     * Traitement de la condition LIMIT
     *
     * @param int $per_page Nombre d'éléments par page de résultat
     * @param int $paged Page courante
     *
     * @return string
     */
    public function clause_limit($per_page = 0, $paged = 1)
    {
        if ($per_page <= 0) :
            return '';
        endif;

        $offset = ($paged - 1) * $per_page;

        return " LIMIT {$offset}, {$per_page}";
    }

    /**
     * Traitement des resultats de requête
     *
     * @param object $results Resultats de requête brut
     * @param string $output Format de sortie
     *
     * @return mixed
     */
    public function parse_output($results, $output = OBJECT)
    {
        $_results = [];
        foreach($results as &$row) :
            $row = (object)array_map('maybe_unserialize', get_object_vars($row));
        endforeach;

        if ($output == OBJECT) :
            $_results = $results;
        elseif ($output == OBJECT_K) :
            foreach ($results as $row ) :
                $var_by_ref = get_object_vars($row);
                $key = array_shift($var_by_ref);
                if (!isset($_results[$key])) :
                    $_results[$key] = $row;
                endif;
            endforeach;
        elseif($output == ARRAY_A || $output == ARRAY_N) :
            foreach ((array)$results as $row) :
                if ($output == ARRAY_N) :
                    $_results[] = array_values(get_object_vars($row));
                else :
                    $_results[] = get_object_vars($row);
                endif;
            endforeach;
        elseif (strtoupper($output) === OBJECT) :
            $_results = $results;
        endif;

        return $_results;
    }

    /**
     * Vérification des arguments de requête
     *
     * @param array $query_vars Variables de requête
     *
     * @return array
     */
    final public function validate($query_vars)
    {
        $_vars = [];
        foreach ($query_vars as $col_name => $value) :
            if (!$col_name = $this->db->existsCol($col_name)) :
                continue;
            endif;
            /** @todo Typage des valeurs ! any cf parse_conditions */
            $_vars[$col_name] = $value;
        endforeach;

        return $_vars;
    }
}
