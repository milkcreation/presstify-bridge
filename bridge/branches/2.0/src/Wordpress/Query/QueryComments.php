<?php declare(strict_types=1);

namespace tiFy\Wordpress\Query;

use tiFy\Wordpress\Contracts\QueryComments as QueryCommentsContract;
use tiFy\Support\Collection;
use WP_Comment;
use WP_Comment_Query;

class QueryComments extends Collection implements QueryCommentsContract
{
    /**
     * Instance de la requête Wordpress de récupération des commentaires.
     * @var WP_Comment_Query
     */
    protected $wp_comment_query;

    /**
     * CONSTRUCTEUR.
     *
     * @param WP_Comment_Query|null $wp_comment_query Requête Wordpress de récupération de post.
     *
     * @return void
     */
    public function __construct(?WP_Comment_Query $wp_comment_query = null)
    {
        if ($this->wp_comment_query = $wp_comment_query) {
            $this->set($this->wp_comment_query->comments ?: []);
        }
    }

    /**
     * @inheritdoc
     */
    public static function createFromArgs(array $args = []): QueryCommentsContract
    {
        return new static(new WP_Comment_Query($args));
    }

    /**
     * @inheritdoc
     */
    public static function createFromIds(array $ids): QueryCommentsContract
    {
        return new static(new WP_Comment_Query(['comment__in' => $ids]));
    }

    /**
     * @inheritdoc
     */
    public function getIds(): array
    {
        return $this->pluck('comment_ID');
    }

    /**
     * {@inheritdoc}
     *
     * @param WP_Comment $item Objet post Wordpress.
     *
     * @return void
     */
    public function walk($item, $key = null)
    {
        $this->items[$key] = new QueryComment($item);
    }

    /**
     * @inheritdoc
     */
    public function WpCommentQuery(): WP_Comment_Query
    {
        return $this->wp_comment_query;
    }
}