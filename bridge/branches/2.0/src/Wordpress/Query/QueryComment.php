<?php declare(strict_types=1);

namespace tiFy\Wordpress\Query;

use tiFy\Wordpress\Contracts\QueryComment as QueryCommentContract;
use tiFy\Wordpress\Contracts\QueryPost as QueryPostContract;
use tiFy\Wordpress\Contracts\QueryUser as QueryUserContract;
use tiFy\Support\ParamsBag;
use tiFy\Support\DateTime;
use WP_Comment;

class QueryComment extends ParamsBag implements QueryCommentContract
{
    /**
     * Instance de commentaire Wordpress.
     * @var WP_Comment
     */
    protected $wp_comment;

    /**
     * CONSTRUCTEUR.
     *
     * @param WP_Comment $wp_comment Instance de commentaire Wordpress.
     *
     * @return void
     */
    public function __construct(WP_Comment $wp_comment)
    {
        $this->wp_comment = $wp_comment;

        $this->set($this->wp_comment->to_array())->parse();
    }

    /**
     * @inheritDoc
     */
    public static function createFromId(int $comment_id): ?QueryCommentContract
    {
        return (($wp_comment = get_comment($comment_id)) && ($wp_comment instanceof WP_Comment))
            ? new static($wp_comment) : null;
    }

    /**
     * @inheritDoc
     */
    public function getAgent(): string
    {
        return $this->get('comment_agent', '');
    }

    /**
     * @inheritDoc
     */
    public function getAuthor(): string
    {
        return $this->get('comment_author', '');
    }

    /**
     * @inheritDoc
     */
    public function getAuthorEmail(): string
    {
        return $this->get('comment_author_email', '');
    }

    /**
     * @inheritDoc
     */
    public function getAuthorIp(): string
    {
        return $this->get('comment_author_ip', '');
    }

    /**
     * @inheritDoc
     */
    public function getAuthorUrl(): string
    {
        return $this->get('comment_author_url', '');
    }

    /**
     * @inheritDoc
     */
    public function getContent(): string
    {
        return $this->get('comment_content', '');
    }

    /**
     * @inheritDoc
     */
    public function getDate(bool $gmt = false): string
    {
        return $gmt
            ? (string)$this->get('comment_date_gmt', '')
            : (string)$this->get('comment_date', '');
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
        return get_edit_comment_link($this->getId());
    }

    /**
     * @inheritDoc
     */
    public function getId(): int
    {
        return intval($this->get('comment_ID', 0));
    }

    /**
     * @inheritDoc
     */
    public function getMeta(string $meta_key, bool $single = false, $default = null)
    {
        return get_comment_meta($this->getId(), $meta_key, $single) ?: $default;
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
    public function getParent(): ?QueryCommentContract
    {
        return self::createFromId($this->getParentId());
    }

    /**
     * @inheritDoc
     */
    public function getParentId(): int
    {
        return intval($this->get('comment_parent', 0));
    }

    /**
     * @inheritDoc
     */
    public function getPost(): QueryPostContract
    {
        return QueryPost::createFromId($this->getPostId());
    }

    /**
     * @inheritDoc
     */
    public function getPostId(): int
    {
        return intval($this->get('comment_post_ID', 0));
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return $this->get('comment_type', '');
    }

    /**
     * @inheritDoc
     */
    public function getUser(): QueryUserContract
    {
        return QueryUser::createFromId($this->getUserId());
    }

    /**
     * @inheritDoc
     */
    public function getUserId(): int
    {
        return intval($this->get('user_id', 0));
    }

    /**
     * @inheritDoc
     */
    public function getWpComment(): WP_Comment
    {
        return $this->wp_comment;
    }

    /**
     * @inheritDoc
     */
    public function isApproved(): bool
    {
        return $this->get('comment_approved', '') == 1;
    }

    /**
     * @inheritDoc
     */
    public function isSpam(): bool
    {
        return $this->get('comment_approved', '') === 'spam';
    }

    /**
     * @inheritDoc
     */
    public function typeIn(array $comment_types): bool
    {
        return in_array($this->getType(), $comment_types);
    }
}