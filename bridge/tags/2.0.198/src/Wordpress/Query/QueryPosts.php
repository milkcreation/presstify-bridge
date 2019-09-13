<?php declare(strict_types=1);

namespace tiFy\Wordpress\Query;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use tiFy\Contracts\Support\Collection as CollectionContract;
use tiFy\Wordpress\Contracts\QueryPosts as QueryPostsContract;
use tiFy\Support\Collection;
use WP_Post;
use WP_Query;

class QueryPosts extends Collection implements QueryPostsContract
{
    /**
     * Instance de la requête Wordpress de récupération des posts.
     * @var WP_Query|null
     */
    protected $wp_query;

    /**
     * CONSTRUCTEUR.
     *
     * @param WP_Query|null $wp_query Requête Wordpress de récupération de post.
     *
     * @return void
     */
    public function __construct(?WP_Query $wp_query = null)
    {
        if ($this->wp_query = $wp_query) {
            $this->set($this->wp_query->posts ?: []);
        }
    }

    /**
     * @inheritDoc
     */
    public static function createFromArgs(array $args = []): QueryPostsContract
    {
        return new static(new WP_Query($args));
    }

    /**
     * @inheritDoc
     */
    public static function createFromEloquent(EloquentCollection $collection): QueryPostsContract
    {
        $items = $collection->toArray();
        array_walk($items, function (array &$item) {
            $item = new WP_Post((object) $item);
        });

        return (new static())->set($items);
    }

    /**
     * @inheritDoc
     */
    public static function createFromGlobals(): QueryPostsContract
    {
        global $wp_query;

        return new static($wp_query);
    }

    /**
     * @inheritDoc
     */
    public static function createFromIds(array $ids): QueryPostsContract
    {
        $post_types = func_get_arg(1) ? : array_keys(get_post_types());

        return new static(new WP_Query(['post__in' => $ids, 'post_type' => $post_types, 'posts_per_page' => -1]));
    }

    /**
     * @inheritDoc
     */
    public function getIds(): array
    {
        return $this->pluck('ID');
    }

    /**
     * @inheritDoc
     */
    public function getTitles(): array
    {
        return $this->pluck('post_title');
    }

    /**
     * {@inheritDoc}
     *
     * @return QueryPostsContract
     */
    public function set($key, $value = null): CollectionContract
    {
        return parent::set($key, $value);
    }

    /**
     * {@inheritDoc}
     *
     * @param WP_Post $item Objet post Wordpress.
     *
     * @return void
     */
    public function walk($item, $key = null)
    {
        $this->items[$key] = new QueryPost($item);
    }

    /**
     * @inheritDoc
     */
    public function WpQuery(): ?WP_Query
    {
        return $this->wp_query;
    }
}