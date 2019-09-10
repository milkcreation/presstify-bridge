<?php

namespace tiFy\Db;

use Illuminate\Support\Arr;
use tiFy\Contracts\Db\DbFactory as DbFactoryContract;
use tiFy\Db\Factory\ResolverTrait;
use tiFy\Support\ParamsBag;
use wpdb;

class DbFactory extends ParamsBag implements DbFactoryContract
{
    use ResolverTrait;

    /**
     * Nom de qualification du controleur de base de données
     * @var string
     */
    protected $name = '';

    /**
     * Nom de qualification la table hors prefixe.
     * @var string
     */
    protected $tableShortName = '';

    /**
     * Nom réel de la table (prefixé)
     * @var string
     */
    protected $tableName = '';

    /**
     * Numéro de version.
     * @var int
     */
    protected $version = 0;

    /**
     * Préfixe des intitulés de colonne
     * @var string
     */
    protected $colPrefix = '';

    /**
     * Liste des noms de colonnes préfixés.
     * @var array
     */
    protected $colNames = [];

    /**
     * Cartographie des alias de colonnes.
     * @var array
     */
    protected $colMap = [];

    /**
     * Liste des attributs de configuration de colonne.
     * @var array
     */
    protected $colAttrs = [];

    /**
     * Nom de la colonne clé primaire.
     * @var null
     */
    protected $primary = null;

    /**
     * Liste des clés d'index.
     * @var array
     */
    protected $indexKeys = [];

    /**
     * Liste des noms de colonnes ouvertes à la recherche de termes.
     * @var string[]
     */
    protected $searchColumns = [];

    /**
     * Moteur de requête SQL.
     * @var null
     */
    protected $sqlEngine;

    /**
     * Identifiant de qualification la table d'enregistrement des metadonnées.
     * @var string
     */
    protected $metaType = '';

    /**
     * Nom de la colonne de jointure de la table d'enregistrement des metadonnées.
     * @var string
     */
    protected $metaJoinCol = '';

    /**
     * Variables de requête privées.
     * @var array
     */
    protected $privateQueryVars = [
        'include',
        /** @todo deprecated alias item__in * */
        'item__in',
        'exclude',
        /** @todo deprecated alias item__not_in * */
        'item__not_in',
        'search',
        /** @todo deprecated alias s * */
        's',
        'fields',
        'per_page',
        'paged',
        'order',
        'orderby',
        'item_meta',
        'limit',
    ];

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification du controleur de base de donnée.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return void
     */
    public function __construct($name, $attrs = [])
    {
        $this->name = $name;
        $this->db = $this;

        $this->set($attrs)->parse();

        if ($this->get('install', false)) {
            $this->install();
        }
    }

    /**
     * Définition des atttributs de configuration d'un colonne (prefixage + cartographie)
     *
     * @param array $columns Liste des colonnes.
     *
     * @return void
     */
    private function _parseColumns($columns)
    {
        foreach ($columns as $alias => $attrs) :
            $defaults = [
                'prefix' => true,
            ];
            $attrs = array_merge($defaults, $attrs);

            $name = $attrs['prefix'] ? $this->colPrefix . $alias : $alias;

            array_push($this->colNames, $name);

            $this->colMap[$alias] = $name;

            $this->colAttrs[$name] = $attrs;
        endforeach;
    }

    /**
     * Définition des attributs de la table de gestion des métadonnées.
     *
     * @param string|boolean|array $meta_type
     *
     * @return void
     */
    private function _parseMeta($meta_type = null)
    {
        if ($meta_type) :
            if (is_string($meta_type)) :
            elseif (is_bool($meta_type)) :
                $meta_type = $this->tableShortName;
            elseif (is_array($meta_type)) :
                $this->metaJoinCol = Arr::get($meta_type, 'join_col', '');
                $meta_type = Arr::get($meta_type, 'meta_type', $this->tableShortName);
            endif;

            $table = $meta_type . 'meta';

            if (!in_array($table, $this->sql()->tables)) :
                array_push($this->sql()->tables, $table);
                $this->sql()->set_prefix($this->sql()->base_prefix);
            endif;

            $this->metaType = $meta_type;
        endif;
    }

    /**
     * Définition de la colonne utilisée en tant que clé primaire.
     *
     * @param string $primary Nom de la colonne de clé primaire.
     *
     * @return void
     */
    private function _parsePrimary($primary = '')
    {
        if (!empty($this->colNames)) :
            $this->primary = ($primary && in_array($primary, $this->colNames)) ? $primary : reset($this->colNames);
        endif;
    }

    /**
     * Définition de la liste des colonnes ouverte à la recherche de terme.
     *
     * @param array $search_columns
     *
     * @return void
     */
    private function _parseSearchColNames($search_columns = [])
    {
        foreach ($search_columns as $alias) :
            if (isset($this->colMap[$alias])) :
                array_push($this->searchColumns, $this->colMap[$alias]);
            endif;
        endforeach;
    }

    /**
     * Définition du moteur (ORM) de traitement des requête de base de données.
     *
     * @param wpdb|object $sql_engine Moteur de traitement des requêtes de base.
     *
     * @return wpdb|object
     */
    private function _parseSQLEngine($sql_engine = null)
    {
        if (is_null($sql_engine) || !($sql_engine instanceof wpdb)) :
            global $wpdb;

            return $this->sqlEngine = $wpdb;
        endif;

        return $this->sqlEngine = $sql_engine;
    }

    /**
     * Définition du nom de la table en base de données.
     *
     * @param string $raw_name Nom de la table de base de données (hors prefixe).
     *
     * @return void
     */
    private function _parseTableName($raw_name = '')
    {
        if (!$raw_name) :
            $raw_name = $this->name;
        endif;

        $this->tableShortName = $raw_name;

        if (!in_array($raw_name, $this->sql()->tables)) :
            array_push($this->sql()->tables, $raw_name);
            $this->sql()->set_prefix($this->sql()->base_prefix);
        endif;

        $this->tableName = $this->sql()->{$raw_name};
    }

    /**
     * Liste des attributs de configuration par défaut.
     *
     * @return array {
     *      Attributs de la table de base de données
     *
     *      @var bool $install Activation de l'installation de la table de base de données
     *      @var int $version Numéro de version de la table
     *      @var string $name Nom de la base de données (hors préfixe)
     *      @var string $primary Colonne de clé primaire
     *      @var string $col_prefix Prefixe des colonnes de la table
     *      @var array $columns {
     *          Liste des attributs de configuration des colonnes
     *      }
     *      @var array $keys {
     *          Liste des attributs de configuration des clefs d'index
     *      }
     *      @var string[] $seach {
     *          Liste des colonnes ouvertes à la recherche
     *      }
     *      @var bool|string|array $meta Activation ou nom de la table de stockage des metadonnées
     *      @var \wpdb|object $sql_engine Moteur (ORM) de requête en base de données
     * }
     */
    public function defaults()
    {
        return [
            'install'    => false,
            'version'    => 1,
            'name'       => '',
            'primary'    => '',
            'col_prefix' => '',
            'columns'    => [],
            'keys'       => [],
            'search'     => [],
            'meta'       => false,
            // moteur de requete SQL global $wpdb par défaut | new \wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
            'sql_engine' => null,
        ];
    }

    /**
     * @inheritdoc
     */
    public function existsCol($name)
    {
        if ($this->isPrivateQueryVar($name)) :
            return '';
        elseif (in_array($name, $this->getColNames())) :
            return $name;
        elseif ($name = $this->getColMap($name)) :
            return $name;
        endif;

        return '';
    }

    /**
     * @inheritdoc
     */
    public function getColAttr($name, $key, $default = '')
    {
        if (!$attrs = $this->getColAttrs($name)) :
            return $default;
        endif;

        if (isset($attrs[$key])) :
            return $attrs[$key];
        endif;

        return $default;
    }

    /**
     * @inheritdoc
     */
    public function getColAttrs($name)
    {
        if (!$name = $this->existsCol($name)) :
            return [];
        endif;

        if (isset($this->colAttrs[$name])) :
            return $this->colAttrs[$name];
        endif;

        return [];
    }

    /**
     * @inheritdoc
     */
    public function getColMap($alias)
    {
        if (isset($this->colMap[$alias])) :
            return $this->colMap[$alias];
        endif;

        return '';
    }

    /**
     * @inheritdoc
     */
    public function getColNames()
    {
        return $this->colNames;
    }

    /**
     * @inheritdoc
     */
    public function getColPrefix()
    {
        return $this->colPrefix;
    }

    /**
     * @inheritdoc
     */
    public function getIndexKeys()
    {
        return $this->indexKeys;
    }

    /**
     * @inheritdoc
     */
    public function getMetaJoinCol()
    {
        return $this->metaJoinCol;
    }

    /**
     * @inheritdoc
     */
    public function getMetaType()
    {
        return $this->metaType;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getPrimary()
    {
        return $this->primary;
    }

    /**
     * @inheritdoc
     */
    public function getSearchColumns()
    {
        return $this->searchColumns ?: [];
    }

    /**
     * @inheritdoc
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @inheritdoc
     */
    public function hasMeta()
    {
        return !empty($this->metaType);
    }

    /**
     * @inheritdoc
     */
    public function hasSearch()
    {
        return !empty($this->searchColumns);
    }

    /**
     * @inheritdoc
     */
    public function install()
    {
        $this->make()->install();
    }

    /**
     * @inheritdoc
     */
    public function isPrimary($name)
    {
        if (!$name = $this->existsCol($name)) :
            return false;
        endif;

        return $this->getPrimary() === $name;
    }

    /**
     * @inheritdoc
     */
    public function isPrivateQueryVar($var)
    {
        return in_array($var, $this->privateQueryVars);
    }

    /**
     * @inheritdoc
     */
    public function parse()
    {
        parent::parse();

        // Définition du numéro de version
        $this->version = $this->get('version');

        // Définition du moteur de requête SQL
        $this->_parseSQLEngine($this->get('sql_engine'));

        // Définition du nom de la table en base de données
        $this->_parseTableName($this->get('name'));

        // Définition du préfixe par défaut des noms de colonnes
        $this->colPrefix = $this->get('col_prefix');

        // Traitement des attributs de colonnes
        $this->_parseColumns($this->get('columns'));

        // Définition de la clé primaire
        $this->_parsePrimary($this->get('primary'));

        // Définition des clés d'index
        $this->indexKeys = $this->get('keys');

        // Définition des colonnes ouvertes à la recherche de termes
        $this->_parseSearchColNames($this->get('search'));

        // Définition de nom de la table de metadonnées en base
        $this->_parseMeta($this->get('meta'));
    }

    /**
     * @inheritdoc
     */
    public function sql()
    {
        return $this->sqlEngine;
    }
}