<?php

namespace tiFy\Db\Factory;

use tiFy\Contracts\Db\DbFactory;
use tiFy\Contracts\Db\DbFactorySelect;

class Select implements DbFactorySelect
{
    use ResolverTrait;

    /**
     * Liste des résultats trouvés.
     * @var mixed
     */
    protected $results = null;

    /**
     * CONSTRUCTEUR.
     *
     * @param array $query Liste des arguments de récupération des éléments.
     * @param DbFactory $db Instance du controleur de base de données associé.
     *
     * @return void
     */
    public function __construct($query = [], DbFactory $db)
    {
        $this->db = $db;

        if (!empty($query)) :
            $this->rows($query);
        endif;
    }

    /**
     * Récupération de la liste des résultats.
     *
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Compte le nombre d'éléments correspondants aux critère de requête.
     *
     * @param array $query_args Critère de requêtede récupération de données en base.
     *
     * @return int
     */
    public function count($query_args = [])
    {
        $name = $this->db->getTableName();
        $primary = $this->db->getPrimary();

        // Traitement des arguments de requête
        $defaults = [
            'item__not_in' => '',
            's'            => '',
            'limit'        => -1,
        ];

        // Traitement des arguments
        $parse = $this->parser();
        $query_args = $parse->query_vars($query_args, $defaults);

        // Traitement de la requête
        /// Selection de la table de base de données
        $query = "SELECT COUNT( {$name}.{$primary} ) FROM {$name}";

        // Conditions de jointure
        $query .= $parse->clause_join();

        /// Conditions définies par les arguments de requête
        if ($clause_where = $parse->clause_where($query_args)) :
            $query .= " " . $clause_where;
        endif;

        /// Recherche de terme
        if ($clause_search = $parse->clause_search($query_args['s'])) :
            $query .= " " . $clause_search;
        endif;

        /// Exclusions
        if ($clause__not_in = $parse->clause__not_in($query_args['item__not_in'])) :
            $query .= " " . $clause__not_in;
        endif;

        /// Groupe
        /*if( $clause_group_by = $parse->clause_group_by() )
            $query .= " ". $clause_group_by;*/

        //// Limite
        if ($query_args['limit'] > -1) :
            $query .= " LIMIT {$query_args['limit']}";
        endif;

        // Résultat
        return (int)$this->db->sql()->get_var($query);
    }

    /**
     * Vérification de l'existance de la valeur d'une données correspondants aux critère de requête.
     *
     * @param null|string $col_name
     * @param string $value
     * @param array $query_args Critère de requêtede récupération de données en base.
     *
     * @return int
     */
    public function has($col_name = null, $value = '', $query_args = [])
    {
        $primary = $this->db->getPrimary();

        // Traitement de l'intitulé de la colonne
        if (is_null($col_name)) :
            $col_name = $primary;
        elseif (!$col_name = $this->db->existsCol($col_name)) :
            return null;
        endif;

        $query_args[$col_name] = $value;

        return $this->count($query_args);
    }

    /**
     * Récupération de l'id d'un élément selon des critères
     *
     * @param array $query_args
     *
     * @return array
     */
    public function id($query_args = [])
    {
        return $this->cell(null, $query_args);
    }

    /**
     * Récupération de la valeur d'un cellule selon des critères.
     *
     * @param null|string $col_name
     *
     * @return array
     */
    public function cell($col_name = null, $query_args = [])
    {
        $name = $this->db->getTableName();
        $primary = $this->db->getPrimary();

        // Traitement de l'intitulé de la colonne
        if (is_null($col_name)) :
            $col_name = $primary;
        elseif (!$col_name = $this->db->existsCol($col_name)) :
            return null;
        endif;

        // Traitement des arguments
        $defaults = [
            'item__in'     => '',
            'item__not_in' => '',
            's'            => '',
            'order'        => 'DESC',
            'orderby'      => $primary,
        ];

        // Traitement des arguments
        $parse = $this->parser();
        $query_args = $parse->query_vars($query_args, $defaults);

        // Traitement de la requête
        /// Selection de la table de base de données
        $query = "SELECT {$name}.{$col_name} FROM {$name}";

        /// Conditions de jointure
        $query .= $parse->clause_join();

        /// Conditions des arguments de requête
        if ($clause_where = $parse->clause_where($query_args)) :
            $query .= " " . $clause_where;
        endif;

        /// Recherche de terme
        if ($clause_search = $parse->clause_search($query_args['s'])) :
            $query .= " " . $clause_search;
        endif;

        /// Inclusions
        if ($clause__in = $parse->clause__in($query_args['item__in'])) :
            $query .= " " . $clause__in;
        endif;

        /// Exclusions
        if ($clause__not_in = $parse->clause__not_in($query_args['item__not_in'])) :
            $query .= " " . $clause__not_in;
        endif;

        /// Groupe
        if ($clause_group_by = $parse->clause_group_by()) :
            $query .= " " . $clause_group_by;
        endif;

        /*
        if( $item__in && ( $orderby === 'item__in' ) )
            $query .= " ORDER BY FIELD( {$this->wpdb_table}.{$this->primary_key}, $item__in )";
        else */
        if ($clause_order = $parse->clause_order($query_args['orderby'], $query_args['order'])) :
            $query .= $clause_order;
        endif;

        if ($var = $this->db->sql()->get_var($query)) :
            return $this->results = maybe_unserialize($var);
        endif;

        return [];
    }

    /** == Récupération de la valeur d'un cellule selon son l'id de l'élément == **/
    public function cell_by_id($id, $col_name)
    {
        if (!$col_name = $this->db->existsCol($col_name)) :
            return null;
        endif;

        if (($item = wp_cache_get($id, $this->db->Name)) && isset($item->{$col_name})) :
            return $this->results = $item->{$col_name};
        else :
            return $this->cell($col_name, [$this->db->Primary => $id]);
        endif;
    }

    /* = COLONNE = */
    /** == Récupération des valeurs d'une colonne de plusieurs éléments selon des critères == **/
    public function col($col_name = null, $query_args = [])
    {
        $name = $this->db->getTableName();
        $primary = $this->db->getPrimary();

        // Traitement de l'intitulé de la colonne
        if (is_null($col_name)) :
            $col_name = $primary;
        elseif (!$col_name = $this->db->existsCol($col_name)) :
            return null;
        endif;

        // Traitement des arguments
        $parse = $this->parser();
        $query_args = $parse->query_vars($query_args);

        // Traitement de la requête
        /// Selection de la table de base de données
        $query = "SELECT {$name}.{$col_name} FROM {$name}";

        // Condition de jointure
        $query .= $parse->clause_join();

        /// Conditions des arguments de requête
        if ($clause_where = $parse->clause_where($query_args)) :
            $query .= " " . $clause_where;
        endif;

        /// Recherche de termes
        if ($clause_search = $parse->clause_search($query_args['s'])) :
            $query .= " " . $clause_search;
        endif;

        /// Inclusions
        if ($clause__in = $parse->clause__in($query_args['item__in'])) :
            $query .= " " . $clause__in;
        endif;

        /// Exclusions
        if ($clause__not_in = $parse->clause__not_in($query_args['item__not_in'])) :
            $query .= " " . $clause__not_in;
        endif;

        /// Groupe
        if ($clause_group_by = $parse->clause_group_by()) :
            $query .= " " . $clause_group_by;
        endif;

        /*
        /// Ordre
        if( $item__in && ( $orderby === 'item__in' ) )
            $query .= " ORDER BY FIELD( {$this->wpdb_table}.{$this->primary_key}, $item__in )";
        else */
        if ($clause_order = $parse->clause_order($query_args['orderby'], $query_args['order'])) :
            $query .= $clause_order;
        endif;

        /// Limite
        if ($query_args['per_page'] > 0) :
            if (!$query_args['paged']) :
                $query_args['paged'] = 1;
            endif;
            $offset = ($query_args['paged'] - 1) * $query_args['per_page'];
            $query .= " LIMIT {$offset}, {$query_args['per_page']}";
        endif;

        // Resultats
        if ($res = $this->db->sql()->get_col($query)) :
            return $this->results = array_map('maybe_unserialize', $res);
        endif;
    }

    /** == Récupération des valeurs de la colonne id de plusieurs éléments selon des critères == **/
    public function col_ids($query_args = [])
    {
        return $this->col(null, $query_args);
    }

    /**
     * Retourne un tableau indexé sous la forme couple clé <> valeur
     *
     * @param string $value_col Colonne utilisée en tant que valeur du couple
     * @param string $key_col Colonne utilisée en tant que clé du couple
     * @param array $query_args Liste des arguments de requête
     * @param string $output Format de sortie
     *
     * @return null|array
     */
    public function pairs($value_col, $key_col = '', $query_args = [])
    {
        // Récupération de la colonne utilisée en tant que clé du couple
        if (!$key_col) :
            $key_col = $this->db->getPrimary();
        endif;

        $query_args['fields'] = [$key_col, $value_col];

        // Traitement de la requête
        if (!$query = $this->parser()->query($query_args)) :
            return [];
        endif;

        // Récupération des resultats de requête
        if (!$items = $this->db->sql()->get_results($query)) :
            return [];
        endif;

        // Tratiement du resultat
        if (!$results = $this->parser()->parse_output($items, OBJECT)) :
            return [];
        endif;

        $pairs = [];
        foreach ($results as $row) :
            $pairs[$row->$key_col] = $row->$value_col;
        endforeach;

        return $pairs;
    }

    /**
     * Récupération des arguments d'un élément selon des critères
     *
     * @param array $query_args
     * @param string $output
     *
     * @return array
     */
    public function row($query_args = [], $output = OBJECT)
    {
        // Traitement des arguments
        $query_args['per_page'] = 1;

        // Bypass
        if (!$ids = $this->col_ids($query_args)) {
            return null;
        }
        $id = current($ids);

        return $this->row_by_id($id, $output);
    }

    /**
     * Récupération d'un élément selon un champ et sa valeur.
     *
     * @param null|string $col_name
     * @param mixed $value
     * @param string $output
     *
     * @return array
     */
    public function row_by($col_name = null, $value, $output = OBJECT)
    {
        $name = $this->db->getTableName();
        $primary = $this->db->getPrimary();

        // Traitement de l'intitulé de la colonne
        if (is_null($col_name)) :
            $col_name = $primary;
        elseif (!$col_name = $this->db->existsCol($col_name)) :
            return null;
        endif;

        $type = $this->db->getColAttr($col_name, 'type');

        if (in_array($type, ['INT', 'BIGINT'])) :
            $query = "SELECT * FROM {$name} WHERE {$name}.{$col_name} = %d";
        else :
            $query = "SELECT * FROM {$name} WHERE {$name}.{$col_name} = %s";
        endif;

        if (!$item = $this->db->sql()->get_row($this->db->sql()->prepare($query, $value))) :
            return;
        endif;

        // Délinéarisation des tableaux
        $item = (object)array_map('maybe_unserialize', get_object_vars($item));

        // Mise en cache
        wp_cache_add($item->{$primary}, $item, $name);

        if ($output == OBJECT) :
            return $this->results = !empty($item) ? $item : [];
        elseif ($output == ARRAY_A) :
            return $this->results = !empty($item) ? get_object_vars($item) : [];
        elseif ($output == ARRAY_N) :
            return $this->results = !empty($item) ? array_values(get_object_vars($item)) : [];
        elseif (strtoupper($output) === OBJECT) :
            return $this->results = !empty($item) ? $item : [];
        else :
            return $this->results = [];
        endif;
    }

    /** == Récupération des arguments d'un élément selon son id == **/
    public function row_by_id($id, $output = OBJECT)
    {
        return $this->row_by(null, $id, $output);
    }

    /* = LIGNES = */
    /** == Récupération des arguments de plusieurs éléments selon des critères == **/
    public function rows($query_args = [], $output = OBJECT)
    {
        // Bypass
        if (!$ids = $this->col_ids($query_args)) :
            return;
        endif;

        $r = [];
        foreach ((array)$ids as $id) :
            $r[] = $this->row_by_id($id, $output);
        endforeach;

        return $this->results = $r;
    }

    /**
     *
     */
    /** == Récupération de l'élément voisin selon un critère == **/
    public function adjacent($id, $previous = true, $query_args = [], $output = OBJECT)
    {
        $name = $this->db->getTableName();
        $primary = $this->db->getPrimary();

        // Traitement des arguments
        $defaults = [
            'item__in'     => '',
            'item__not_in' => '',
            's'            => '',
        ];

        // Traitement des arguments
        $parse = $this->parser();
        $query_args = $parse->query_vars($query_args, $defaults);
        unset($query_args[$primary]);

        $op = $previous ? '<' : '>';
        $query_args['order'] = $previous ? 'DESC' : 'ASC';
        $query_args['$orderby'] = $primary;

        // Traitement de la requête
        /// Selection de la table de base de données
        $query = "SELECT * FROM {$name}";

        // Condition de jointure
        $query .= $parse->clause_join();

        /// Conditions definies par les arguments de requête
        if ($clause_where = $parse->clause_where($query_args)) :
            $query .= " " . $clause_where;
        endif;

        /// Conditions spécifiques
        $query .= " AND {$name}.{$primary} $op %d";

        /// Recherche de terme
        if ($clause_search = $parse->clause_search($query_args['s'])) :
            $query .= " " . $clause_search;
        endif;

        /// Inclusions
        if ($clause__in = $parse->clause__in($query_args['item__in'])) :
            $query .= " " . $clause__in;
        endif;

        /// Exclusions
        if ($clause__not_in = $parse->clause__not_in($query_args['item__not_in'])) :
            $query .= " " . $clause__not_in;
        endif;

        /// Groupe
        if ($clause_group_by = $parse->clause_group_by()) :
            $query .= " " . $clause_group_by;
        endif;

        /// Ordre
        if ($clause_order = $parse->clause_order($query_args['orderby'], $query_args['order'])) :
            $query .= $clause_order;
        endif;

        if (!$item = $this->db->sql()->get_row($this->db->sql()->prepare($query, $id))) :
            return;
        endif;

        // Délinéarisation des tableaux
        $item = (object)array_map('maybe_unserialize', get_object_vars($item));

        // Mise en cache
        wp_cache_add($item->{$primary}, $item, $name);

        if ($output == OBJECT) :
            return !empty($item) ? $item : null;
        elseif ($output == ARRAY_A) :
            return !empty($item) ? get_object_vars($item) : null;
        elseif ($output == ARRAY_N) :
            return !empty($item) ? array_values(get_object_vars($item)) : null;
        elseif (strtoupper($output) === OBJECT) :
            return !empty($item) ? $item : null;
        endif;
    }

    /* == Récupération de l'élément précédent == */
    public function previous($id, $query_args = [], $output = OBJECT)
    {
        return $this->adjacent($id, true, $query_args, $output);
    }

    /* == Récupération de l'élément suivant == */
    public function next($id, $query_args = [], $output = OBJECT)
    {
        return $this->adjacent($id, false, $query_args, $output);
    }
}
