<?php declare(strict_types=1);

namespace tiFy\Wordpress\Query;

use Illuminate\Database\Eloquent\{
    Collection as EloquentCollection,
    Model as EloquentModel
};
use tiFy\Support\{Arr, ParamsBag};
use tiFy\Wordpress\Contracts\{
    Database\TaxonomyBuilder,
    Query\PaginationQuery as PaginationQueryContract,
    Query\QueryTerm as QueryTermContract
};
use tiFy\Wordpress\Database\Model\Term as Model;
use WP_Term, WP_Term_Query;

/**
 * @property-read int $term_id
 * @property-read string $name
 * @property-read string $slug
 * @property-read string $term_group
 * @property-read int $term_taxonomy_id
 * @property-read string $taxonomy
 * @property-read string $description
 * @property-read int $parent
 * @property-read int $count
 * @property-read string $filter
 */
class QueryTerm extends ParamsBag implements QueryTermContract
{
    /**
     * Liste des classes de rappel d'instanciation selon la taxonomie.
     * @var string[][]|array
     */
    protected static $builtInClasses = [];

    /**
     * Classe de rappel d'instanciation
     * @var string|null
     */
    protected static $fallbackClass;

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
     * Indice de récupération des éléments non associés.
     * @var bool
     */
    protected static $hideEmpty = false;

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
    public static function build(WP_Term $wp_term): QueryTermContract
    {
        $classes = self::$builtInClasses;
        $taxonomy = $wp_term->taxonomy;

        $class = $classes[$taxonomy] ?? (self::$fallbackClass ?: static::class);

        return class_exists($class) ? new $class($wp_term) : new static($wp_term);
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
            return static::build($id);
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
    public static function createFromEloquent(EloquentModel $model): ?QueryTermContract
    {
        return static::createFromId((new WP_Term((object)$model->getAttributes()))->term_id ?: 0);
    }

    /**
     * @inheritDoc
     */
    public static function createFromGlobal(): ?QueryTermContract
    {
        global $wp_query;

        return $wp_query->is_tax() || $wp_query->is_category() || $wp_query->is_tag()
            ? self::createFromId($wp_query->queried_object_id) : null;
    }

    /**
     * @inheritDoc
     */
    public static function createFromId(int $term_id): ?QueryTermContract
    {
        if ($term_id && ($wp_term = get_term($term_id)) && ($wp_term instanceof WP_Term)) {
            return static::is($instance = static::build($wp_term)) ? $instance : null;
        } else {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public static function createFromSlug(string $term_slug, ?string $taxonomy = null): ?QueryTermContract
    {
        $wp_term = get_term_by('slug', $term_slug, $taxonomy ?? static::$taxonomy);

        return ($wp_term instanceof WP_Term) ? static::createFromId($wp_term->term_id ?? 0) : null;
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
    public static function fetchFromEloquent(EloquentCollection $collection): array
    {
        $instances = [];
        foreach ($collection->toArray() as $item) {
            if ($instance = static::createFromId((new WP_Term((object)$item))->term_id ?: 0)) {
                $instances[] = $instance;
            }
        }

        return $instances;
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
        $terms = $wp_term_query->get_terms();
        $per_page = $wp_term_query->query_vars['number'] ?: -1;
        $count = count($terms);
        $offset = $wp_term_query->query_vars['offset'] ?: 0;

        if ($per_page > 0) {
            $wp_term_query_count = new WP_Term_Query(array_merge($wp_term_query->query_vars, [
                'count'  => false,
                'number' => 0,
                'offset' => 0,
                'fields' => 'count',
            ]));

            $total = (int)$wp_term_query_count->get_terms();
            $pages = (int)ceil($total / $per_page);
            $page = (int)ceil(($offset + 1) / $per_page);
        } else {
            $pages = 1;
            $page = 1;
            $total = (int)count($terms);
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

        $results = [];
        foreach ($terms as $wp_term) {
            $instance = static::build($wp_term);

            if (($taxonomy = static::$taxonomy) && ($taxonomy !== 'any')) {
                if ($instance->taxIn($taxonomy)) {
                    $results[] = $instance;
                }
            } else {
                $results[] = $instance;
            }
        }

        static::pagination()->set(compact('results'))->parse();

        return $results;
    }

    /**
     * @inheritDoc
     */
    public static function is($instance): bool
    {
        return $instance instanceof static &&
            ((($taxonomy = static::$taxonomy) && ($taxonomy !== 'any')) ? $instance->taxIn($taxonomy) : true);
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

        if (!isset($args['hide_empty'])) {
            $args['hide_empty'] = static::$hideEmpty;
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
    public static function setBuiltInClass(string $post_type, string $classname): void
    {
        self::$builtInClasses[$post_type] = $classname;
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
    public static function setFallbackClass(string $classname): void
    {
        self::$fallbackClass = $classname;
    }

    /**
     * @inheritDoc
     */
    public static function setTaxonomy(string $taxonomy): void
    {
        static::$taxonomy = $taxonomy;
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
    public function getMeta(string $meta_key, bool $single = false, $default = null)
    {
        return get_term_meta($this->getId(), $meta_key, $single) ?: $default;
    }

    /**
     * @inheritDoc
     */
    public function getMetaMulti(string $meta_key, $default = null)
    {
        return $this->getMeta($meta_key, false, $default);
    }

    /**
     * @inheritDoc
     */
    public function getMetaSingle(string $meta_key, $default = null)
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
    public function save(array $termdata): void
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

    /**
     * @inheritDoc
     */
    public function taxIn($taxonomies): bool
    {
        return in_array((string)$this->getTaxonomy(), Arr::wrap($taxonomies));
    }
}