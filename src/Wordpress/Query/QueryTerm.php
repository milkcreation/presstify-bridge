<?php declare(strict_types=1);

namespace tiFy\Wordpress\Query;

use tiFy\Contracts\Support\ParamsBag as ParamsBagContract;
use tiFy\Support\ParamsBag;
use tiFy\Wordpress\Contracts\{Database\TaxonomyBuilder, Query\QueryTerm as QueryTermContract};
use tiFy\Wordpress\Database\Model\Term as Model;
use WP_Term;
use WP_Term_Query;

class QueryTerm extends ParamsBag implements QueryTermContract
{
    /**
     * Liste des arguments de requête de récupération des éléments par défaut.
     * @var array
     */
    protected static $defaultArgs = [];

    /**
     * Nom de qualification de la taxonomie associée.
     * @var string
     */
    protected static $taxonomy = '';

    /**
     * Instance de la dernière requête de récupération d'une liste d'éléments.
     * @var ParamsBag|null
     */
    protected static $query;

    /**
     * Instance du modèle de base de données associé.
     * @var TaxonomyBuilder
     */
    protected $db;

    /**
     * Instance de terme de taxonomie Wordpress.
     * @var WP_Term
     */
    protected $wpTerm;

    /**
     * CONSTRUCTEUR.
     *
     * @param WP_Term|null $wp_term Instance de terme de taxonomie Wordpress.
     *
     * @return void
     */
    public function __construct(?WP_Term $wp_term = null)
    {
        if ($this->wpTerm = $wp_term instanceof WP_Term ? $wp_term : null) {
            $this->set($this->wpTerm->to_array())->parse();
        }
    }

    /**
     * @inheritDoc
     */
    public static function create($id = null, ...$args): ?QueryTermContract
    {
        if (is_numeric($id)) {
            return static::createFromId((int)$id);
        } elseif (is_string($id)) {
            return static::createFromSlug($id, ...$args);
        } elseif ($id instanceof WP_Term) {
            return (new static($id));
        } else {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public static function createFromId(int $term_id): ?QueryTermContract
    {
        return (($wp_term = get_term($term_id)) && ($wp_term instanceof WP_Term))
            ? new static($wp_term) : null;
    }

    /**
     * @inheritDoc
     */
    public static function createFromGlobal(): ?QueryTermContract
    {
        global $wp_query;

        return $wp_query->is_tax() ? self::createFromId($wp_query->queried_object_id) : null;
    }

    /**
     * @inheritDoc
     */
    public static function createFromSlug(string $term_slug, ?string $taxonomy = null): ?QueryTermContract
    {
        $taxonomy = $taxonomy ?? static::$taxonomy;

        return (($wp_term = get_term_by('slug', $term_slug, $taxonomy)) && ($wp_term instanceof WP_Term))
            ? new static($wp_term) : null;
    }

    /**
     * @inheritDoc
     */
    public static function fetch($query): array
    {
        if (is_array($query)) {
            return static::queryFromArgs($query);
        } elseif ($query instanceof WP_Term_Query) {
            return static::queryFromWpTermQuery($query);
        } else {
            return [];
        }
    }

    /**
     * @inheritDoc
     */
    public static function parseQueryArgs(array $args = []): array
    {
        if ($taxonomy = static::$taxonomy) {
            $args['taxonomy'] = $taxonomy;
        }

        return array_merge(static::$defaultArgs, $args);
    }

    /**
     * @inheritDoc
     */
    public static function query(): ParamsBagContract
    {
        if (is_null(static::$query)) {
            static::$query = new ParamsBag();
        }

        return static::$query;
    }

    /**
     * @inheritDoc
     */
    public static function queryFromArgs(array $args = []): array
    {
        return static::queryFromWpTermQuery(new WP_Term_Query(static::parseQueryArgs($args)));
    }

    /**
     * @inheritDoc
     */
    public static function queryFromIds(array $ids): array
    {
        return static::queryFromWpTermQuery(new WP_Term_Query(static::parseQueryArgs(['include' => $ids])));
    }

    /**
     * @inheritDoc
     */
    public static function queryFromWpTermQuery(WP_Term_Query $wp_term_query): array
    {
        $per_page = $wp_term_query->query_vars['number'] ?: -1;
        $count = count($wp_term_query->terms);
        $offset = $wp_term_query->query_vars['offset'] ?: 1;

        if ($per_page > 0) {
            $args = $wp_term_query->query_vars;
            $args['count'] = false;
            $args['number'] = 0;
            $args['offset'] = 1;
            $args['fields'] = 'count';

            $wp_term_query_count = new WP_Term_Query($args);

            $total = (int)$wp_term_query_count->get_terms();
            $pages = (int)ceil($total / $count);
            $page = (int)ceil($offset / $per_page);
        } else {
            $pages = 1;
            $page = 1;
            $total = (int)count($wp_term_query->terms);
        }

        static::query()->clear()->set([
            'args'     => $wp_term_query->query_vars,
            'count'    => $count,
            'pages'    => $pages,
            'page'     => $page,
            'per_page' => $per_page,
            'total'    => $total,
        ]);

        $data = $wp_term_query->terms;

        array_walk($data, function (WP_Term &$wp_term) {
            $wp_term = new static($wp_term);
        });

        static::query()->set(compact('data'));

        return $data;
    }

    /**
     * @inheritDoc
     */
    public static function setDefaultArgs(array $args): void
    {
        self::$defaultArgs = $args;
    }

    /**
     * @inheritDoc
     */
    public static function setTaxonomy(string $taxonomy): void
    {
        self::$taxonomy = $taxonomy;
    }

    /**
     * @inheritDoc
     */
    public function db(): TaxonomyBuilder
    {
        if (!$this->db) {
            $this->db = (new Model())->find($this->getId());
        }

        return $this->db;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return (string)$this->get('description', '');
    }

    /**
     * @inheritDoc
     */
    public function getId(): int
    {
        return intval($this->get('term_id', 0));
    }

    /**
     * @inheritDoc
     */
    public function getMeta($meta_key, $single = false, $default = null)
    {
        return get_term_meta($this->getId(), $meta_key, $single) ?: $default;
    }

    /**
     * @inheritDoc
     */
    public function getMetaMulti($meta_key, $default = null)
    {
        return $this->getMeta($meta_key, false, $default);
    }

    /**
     * @inheritDoc
     */
    public function getMetaSingle($meta_key, $default = null)
    {
        return $this->getMeta($meta_key, true, $default);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return (string)$this->get('name', '');
    }

    /**
     * @inheritDoc
     */
    public function getPermalink(): string
    {
        return get_term_link($this->getWpTerm());
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return (string)$this->get('slug', '');
    }

    /**
     * @inheritDoc
     */
    public function getTaxonomy(): string
    {
        return (string)$this->get('taxonomy', '');
    }

    /**
     * @inheritDoc
     */
    public function getWpTerm(): WP_Term
    {
        return $this->wpTerm;
    }

    /**
     * @inheritDoc
     */
    public function save($termdata): void
    {
        $p = ParamsBag::createFromAttrs($termdata);
        $columns = $this->db()->getConnection()->getSchemaBuilder()->getColumnListing($this->db()->getTable());

        $data = [];
        foreach ($columns as $col) {
            if ($p->has($col)) {
                $data[$col] = $p->get($col);
            }
        }

        if ($data) {
            $this->db()->where(['term_id' => $this->getId()])->update($data);
        }

        $taxdata = [];
        foreach (['description', 'parent', 'count'] as $col) {
            if ($p->has($col)) {
                $taxdata[$col] = $p->get($col);
            }
        }

        if ($taxdata) {
            $this->db()->taxonomy()->where(['term_id' => $this->getId()])->update($taxdata);
        }


        if ($p->has('meta')) {
            $this->saveMeta($p->get('meta'));
        }
    }

    /**
     * @inheritDoc
     */
    public function saveMeta($key, $value = null): void
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $k => $v) {
            $this->db()->saveMeta($k, $v);
        }
    }
}