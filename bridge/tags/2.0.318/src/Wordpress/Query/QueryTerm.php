<?php declare(strict_types=1);

namespace tiFy\Wordpress\Query;

use tiFy\Support\ParamsBag;
use tiFy\Wordpress\Contracts\{
    Database\TaxonomyBuilder,
    Query\PaginationQuery as PaginationQueryContract,
    Query\QueryTerm as QueryTermContract
};
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
     * Instance de la pagination la dernière requête de récupération d'une liste d'éléments.
     * @var PaginationQueryContract|null
     */
    protected static $pagination;

    /**
     * Nom de qualification de la taxonomie associée.
     * @var string
     */
    protected static $taxonomy = '';

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
        } elseif ($id instanceof QueryTermContract) {
            return static::createFromId($id->getId());
        } elseif (is_null($id) && ($instance = static::createFromGlobal())) {
            if (($taxonomy = static::$taxonomy)) {
                return $instance->getTaxonomy() === $taxonomy ? $instance : null;
            } else {
                return $instance;
            }
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
            return static::fetchFromArgs($query);
        } elseif ($query instanceof WP_Term_Query) {
            return static::fetchFromWpTermQuery($query);
        } else {
            return [];
        }
    }

    /**
     * @inheritDoc
     */
    public static function fetchFromArgs(array $args = []): array
    {
        return static::fetchFromWpTermQuery(new WP_Term_Query(static::parseQueryArgs($args)));
    }

    /**
     * @inheritDoc
     */
    public static function fetchFromIds(array $ids): array
    {
        return static::fetchFromWpTermQuery(new WP_Term_Query(static::parseQueryArgs(['include' => $ids])));
    }

    /**
     * @inheritDoc
     */
    public static function fetchFromWpTermQuery(WP_Term_Query $wp_term_query): array
    {
        $per_page = $wp_term_query->query_vars['number'] ?: -1;
        $count = count($wp_term_query->terms);
        $offset = $wp_term_query->query_vars['offset'] ?: 1;

        if ($per_page > 0) {
            $wp_term_query_count = new WP_Term_Query(array_merge($wp_term_query->query_vars, [
                'count'  => false,
                'number' => 0,
                'offset' => 1,
                'fields' => 'count',
            ]));

            $total = (int)$wp_term_query_count->get_terms();
            $pages = (int)ceil($total / $count);
            $page = (int)ceil($offset / $per_page);
        } else {
            $pages = 1;
            $page = 1;
            $total = (int)count($wp_term_query->terms);
        }

        static::pagination()->clear()->set([
            'count'        => $count,
            'current_page' => $page,
            'last_page'    => $pages,
            'per_page'     => $per_page,
            'query_obj'    => $wp_term_query,
            'results'      => [],
            'total'        => $total,
        ]);

        $results = $wp_term_query->terms;

        array_walk($results, function (WP_Term &$wp_term) {
            $wp_term = new static($wp_term);
        });

        static::pagination()->set(compact('results'));

        return $results;
    }

    /**
     * @inheritDoc
     */
    public static function pagination(): PaginationQueryContract
    {
        if (is_null(static::$pagination)) {
            static::$pagination = new PaginationQuery();
        }

        return static::$pagination;
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
     * {@inheritDoc}
     *
     * @deprecated
     */
    public static function queryFromArgs(array $args = []): array
    {
        return static::fetchFromArgs($args);
    }

    /**
     * {@inheritDoc}
     *
     * @deprecated
     */
    public static function queryFromIds(array $ids): array
    {
        return static::fetchFromIds($ids);
    }

    /**
     * {@inheritDoc}
     *
     * @deprecated
     */
    public static function queryFromWpTermQuery(WP_Term_Query $wp_term_query): array
    {
        return static::fetchFromWpTermQuery($wp_term_query);
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