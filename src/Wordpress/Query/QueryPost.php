<?php declare(strict_types=1);

namespace tiFy\Wordpress\Query;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use tiFy\Contracts\{PostType\PostTypeFactory, PostType\PostTypeStatus, Support\ParamsBag as ParamsBagContract};
use tiFy\Support\{DateTime, ParamsBag};
use tiFy\Support\Proxy\{Cache, PostType};
use tiFy\Wordpress\{Contracts\Database\PostBuilder,
    Contracts\Query\QueryComment as QueryCommentContract,
    Contracts\Query\QueryPost as QueryPostContract,
    Database\Model\Post as ModelPost,
    Proxy\Media};
use WP_Post;
use WP_Query;
use WP_Term_Query;
use WP_User;

class QueryPost extends ParamsBag implements QueryPostContract
{
    /**
     * Liste des arguments de requête de récupération des éléments par défaut.
     * @var array
     */
    protected static $defaultArgs = [];

    /**
     * Nom de qualification du type de post ou liste de types de post associés.
     * @var string|string[]|null
     */
    protected static $postType = 'any';

    /**
     * Instance de la dernière requête de récupération d'une liste d'éléments?
     * @var ParamsBag|null
     */
    protected static $query;

    /**
     * Instance du modèle de base de données associé.
     * @var PostBuilder
     */
    protected $db;

    /**
     * Instance du parent.
     * {@internal Variation uniquement}
     * @var QueryPost|false|null
     */
    protected $parent;

    /**
     * Instance de post Wordpress.
     * @var WP_Post|null
     */
    protected $wpPost;

    /**
     * CONSTRUCTEUR.
     *
     * @param WP_Post|null $wp_post Instance de post Wordpress.
     *
     * @return void
     */
    public function __construct(?WP_Post $wp_post = null)
    {
        if ($this->wpPost = $wp_post instanceof WP_Post ? $wp_post : null) {
            $this->set($this->wpPost->to_array())->parse();
        }
    }

    /**
     * @inheritDoc
     */
    public static function create($id = null, ...$args): ?QueryPostContract
    {
        if (is_numeric($id)) {
            return static::createFromId((int)$id);
        } elseif (is_string($id)) {
            return static::createFromName($id);
        } elseif ($id instanceof WP_Post) {
            return (new static($id));
        } elseif (is_null($id) && ($instance = static::createFromGlobal())) {
            if (($postType = static::$postType) && ($postType!== 'any')) {
                return $instance->typeIn($postType) ? $instance : null;
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
    public static function createFromEloquent(EloquentModel $model): ?QueryPostContract
    {
        return new static(new WP_Post((object)$model->getAttributes()));
    }

    /**
     * @inheritDoc
     */
    public static function createFromGlobal(): ?QueryPostContract
    {
        global $post;

        return $post instanceof WP_Post ? new static($post) : null;
    }

    /**
     * @inheritDoc
     */
    public static function createFromId(int $post_id): ?QueryPostContract
    {
        return ($post_id && ($wp_post = get_post($post_id)) && ($wp_post instanceof WP_Post))
            ? new static($wp_post) : null;
    }

    /**
     * @inheritDoc
     */
    public static function createFromPostdata(array $postdata): ?QueryPostContract
    {
        return isset($postdata['ID']) ? new static(new WP_Post((object)$postdata)) : null;
    }

    /**
     * @inheritDoc
     */
    public static function createFromName(string $name): ?QueryPostContract
    {
        $wpQuery = new WP_Query(static::parseQueryArgs(['name' => $name]));

        return ($wpQuery->found_posts == 1) ? new static(current($wpQuery->posts)) : null;
    }

    /**
     * @inheritDoc
     */
    public static function fetch($query = null): array
    {
        if (is_array($query)) {
            return static::queryFromArgs($query);
        } elseif ($query instanceof WP_Query) {
            return static::queryFromWpQuery($query);
        } elseif (is_null($query)) {
            return static::queryFromGlobals();
        } else {
            return [];
        }
    }

    /**
     * @inheritDoc
     */
    public static function parseQueryArgs(array $args = []): array
    {
        if (!isset($args['post_type'])) {
            $args['post_type'] = static::$postType ?: 'any';
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
        return static::queryFromWpQuery(new WP_Query(static::parseQueryArgs($args)));
    }

    /**
     * @inheritDoc
     */
    public static function queryFromEloquent(EloquentCollection $collection): array
    {
        $items = $collection->toArray();
        array_walk($items, function (array &$item) {
            $item = new static(new WP_Post((object)$item));
        });

        return $items;
    }

    /**
     * @inheritDoc
     */
    public static function queryFromGlobals(): array
    {
        global $wp_query;

        return static::queryFromWpQuery($wp_query);
    }

    /**
     * @inheritDoc
     */
    public static function queryFromIds(array $ids): array
    {
        $args = static::parseQueryArgs(['post__in' => $ids, 'posts_per_page' => -1]);
        if (!isset($args['post_type'])) {
            $args['post_type'] = 'any';
        }
        $args['post__in'] = $ids;
        $args['posts_per_page'] = -1;

        return static::queryFromWpQuery(new WP_Query($args));
    }

    /**
     * @inheritDoc
     */
    public static function queryFromWpQuery(WP_Query $wp_query): array
    {
        $total = (int)$wp_query->found_posts;
        $per_page = (int)$wp_query->get('posts_per_page');
        $current = $wp_query->get('paged') ? : 1;

        static::query()->clear()->set([
            'args'     => $wp_query->query,
            'count'    => (int)$wp_query->post_count,
            'pages'    => $per_page < 0 ? 1 : (int)$wp_query->max_num_pages,
            'page'     => $per_page < 0 ? 1 : (int)$current,
            'per_page' => $per_page,
            'total'    => $total,
        ]);

        $data = $wp_query->posts ?? [];

        array_walk($data, function (WP_Post &$wp_post) {
            $wp_post = new static($wp_post);
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
    public static function setPostType($post_type): void
    {
        self::$postType = $post_type;
    }

    /**
     * @inheritDoc
     */
    public function cacheable(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function cacheAdd(string $key, $value = null): QueryPostContract
    {
        if ($this->cacheable()) {
            Cache::put($this->cacheKey(), array_merge($this->cacheGet(), [$key => $value]), $this->cacheExpire());
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function cacheClear(): QueryPostContract
    {
        if ($this->cacheable()) {
            Cache::forget($this->cacheKey());
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function cacheCreate(): QueryPostContract
    {
        $this->cacheClear();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function cacheExpire(): ?int
    {
        return 3600 * 24;
    }

    /**
     * @inheritDoc
     */
    public function cacheGet(?string $key = null, $default = null)
    {
        if ($this->cacheable()) {
            $cache = Cache::get($this->cacheKey()) ?: [];
        } else {
            $cache = [];
        }

        return is_null($key) ? $cache : ($cache[$key] ?? $default);
    }

    /**
     * @inheritDoc
     */
    public function cacheHas(string $key): bool
    {
        return $this->cacheable() && !is_null($this->cacheGet($key, null));
    }

    /**
     * @inheritDoc
     */
    public function cacheKey(): string
    {
        return "{$this->getType()}_{$this->getId()}";
    }

    /**
     * @inheritDoc
     */
    public function db(): PostBuilder
    {
        if (!$this->db) {
            $this->db = (new ModelPost())->find($this->getId());
        }

        return $this->db;
    }

    /**
     * @inheritDoc
     */
    public function getAuthorId(): int
    {
        return (int)$this->get('post_author', 0);
    }

    /**
     * @inheritDoc
     */
    public function getClass(array $classes = [], bool $html = true)
    {
        $classes = get_post_class($classes, $this->getId());

        return $html ? 'class="' . join(' ', $classes) . '"' : $classes;
    }

    /**
     * @inheritDoc
     */
    public function getComment(int $id): ?QueryCommentContract
    {
        return ($res = QueryComment::createFromId($id)) && ($res->getPostId() === $this->getId()) ? $res : null;
    }

    /**
     * @inheritDoc
     */
    public function getComments(array $args = []): array
    {
        return QueryComment::queryFromArgs(array_merge(['post_id' => $this->getId()], $args));
    }

    /**
     * @inheritDoc
     */
    public function getContent(bool $raw = false): string
    {
        $content = (string)$this->get('post_content', '');

        if (!$raw) {
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
        }

        return $content;
    }

    /**
     * @inheritDoc
     */
    public function getDate(bool $gmt = false): string
    {
        return $gmt ? (string)$this->get('post_date_gmt', '') : (string)$this->get('post_date', '');
    }

    /**
     * @inheritDoc
     */
    public function getDateTime(bool $gmt = false): DateTime
    {
        return Datetime::createFromTimeString($this->getDate($gmt));
    }

    /**
     * @inheritDoc
     */
    public function getEditLink(): string
    {
        return get_edit_post_link($this->getId());
    }

    /**
     * @inheritDoc
     */
    public function getExcerpt(bool $raw = false): string
    {
        if (!$excerpt = (string)$this->get('post_excerpt', '')) {
            $text = $this->get('post_content', '');

            // @see /wp-includes/post-template.php \get_the_excerpt()
            $text = strip_shortcodes($text);
            $text = apply_filters('the_content', $text);
            $text = str_replace(']]>', ']]&gt;', $text);

            $excerpt_length = apply_filters('excerpt_length', 55);
            $excerpt_more = apply_filters('excerpt_more', ' ' . '[&hellip;]');
            $excerpt = wp_trim_words($text, $excerpt_length, $excerpt_more);
        }

        return $raw ? $excerpt : ($excerpt ? (string)apply_filters('get_the_excerpt', $excerpt) : '');
    }

    /**
     * @inheritDoc
     */
    public function getGuid(): string
    {
        return (string)$this->get('guid', '');
    }

    /**
     * @inheritDoc
     */
    public function getId(): int
    {
        return (int)$this->get('ID', 0);
    }

    /**
     * @inheritDoc
     */
    public function getMeta(string $meta_key, bool $single = false, $default = null)
    {
        return get_post_meta($this->getId(), $meta_key, $single) ?: $default;
    }

    /**
     * @inheritDoc
     */
    public function getMetaKeys(bool $registered = true): array
    {
        if ($registered) {
            return PostType::meta()->keys((string)$this->getType());
        } else {
            return get_post_custom_keys($this->getId()) ?: [];
        }
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
    public function getModified(bool $gmt = false): string
    {
        return $gmt ? (string)$this->get('post_modified_gmt', '') : (string)$this->get('post_modified', '');
    }

    /**
     * @inheritDoc
     */
    public function getModifiedDateTime(bool $gmt = false): DateTime
    {
        return Datetime::createFromTimeString($this->getModified($gmt));
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->getSlug();
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?QueryPostContract
    {
        if (is_null($this->parent) && ($parent_id = $this->getParentId())) {
            $this->parent = static::createFromId($parent_id) ?: false;
        } else {
            $this->parent = false;
        }

        return $this->parent ?: null;
    }

    /**
     * @inheritDoc
     */
    public function getParentId(): int
    {
        return (int)$this->get('post_parent', 0);
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return rtrim(str_replace(home_url(), '', $this->getPermalink()), '/');
    }

    /**
     * @inheritDoc
     */
    public function getPermalink(): string
    {
        return get_permalink($this->getId());
    }

    /**
     * {@inheritDoc}
     *
     * @deprecated
     */
    public function getPost()
    {
        return $this->getWpPost();
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return (string)$this->get('post_name', '');
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): PostTypeStatus
    {
        return PostType::status($this->get('post_status', ''));
    }

    /**
     * @inheritDoc
     */
    public function getTerms($taxonomy, array $args = []): array
    {
        $args['taxonomy'] = $taxonomy;
        $args['object_ids'] = $this->getId();

        return (new WP_Term_Query($args))->terms;
    }

    /**
     * @inheritDoc
     */
    public function getThumbnail($size = 'post-thumbnail', array $attrs = []): string
    {
        return get_the_post_thumbnail($this->getId(), $size, $attrs);
    }

    /**
     * @inheritDoc
     */
    public function getThumbnailSrc($size = 'post-thumbnail'): string
    {
        return get_the_post_thumbnail_url($this->getId(), $size);
    }

    /**
     * @inheritDoc
     */
    public function getThumbnailBase64Src($size = 'thumbnail'): ?string
    {
        return ($id = (int)get_post_thumbnail_id($this->getId())) ? Media::getBase64Src($id) : null;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(bool $raw = false): string
    {
        $title = (string)$this->get('post_title', '');

        return $raw ? $title : (string)apply_filters('the_title', $title, $this->getId());
    }

    /**
     * @inheritDoc
     */
    public function getType(): ?PostTypeFactory
    {
        return PostType::get($this->get('post_type', ''));
    }

    /**
     * @inheritDoc
     */
    public function getWpPost(): ?WP_Post
    {
        return $this->wpPost;
    }

    /**
     * @inheritDoc
     */
    public function hasTerm($term, string $taxonomy): bool
    {
        return has_term($term, $taxonomy, $this->getWpPost());
    }

    /**
     * @inheritDoc
     */
    public function save(array $postdata): void
    {
        $p = (new ParamsBag())->set($postdata);
        $columns = $this->db()->getConnection()->getSchemaBuilder()->getColumnListing($this->db()->getTable());

        $update = [];
        foreach ($columns as $col) {
            if ($p->has($col)) {
                $update[$col] = $p->get($col);
            }
        }

        if ($update) {
            $this->db()->where(['ID' => $this->getId()])->update($update);
        }

        if ($p->has('meta')) {
            $this->saveMeta($p->get('meta'));
        }
    }

    /**
     * @inheritDoc
     */
    public function saveComment(string $content, array $commentdata = [], ?WP_User $wp_user = null): int
    {
        $user = $wp_user ? new QueryUser($wp_user) : QueryUser::createFromGlobal();

        $commentdata = array_merge([
            'comment_ID'           => 0,
            'comment_post_ID'      => $this->getId(),
            'comment_author'       => $user->getDisplayName(),
            'comment_author_email' => $user->getEmail(),
            'comment_author_url'   => $user->getUrl(),
            'comment_author_IP'    => request()->ip(),
            'comment_content'      => $content,
            'comment_agent'        => request()->userAgent(),
            'comment_type'         => '',
            'comment_parent'       => 0,
            'comment_approved'     => 1,
            'user_id'              => $user->getId(),
            'meta'                 => [],
        ], $commentdata);

        if ($comment_id = wp_insert_comment($commentdata)) {
            foreach ($commentdata['meta'] as $k => $v) {
                add_comment_meta($comment_id, $k, $v);
            }

            return $comment_id;
        } else {
            return 0;
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
    public function typeIn(array $post_types): bool
    {
        return in_array((string)$this->getType(), $post_types);
    }
}