<?php

namespace tiFy\PostType\Query;

use tiFy\Contracts\PostType\PostQueryItem as PostQueryItemContract;
use tiFy\Kernel\Params\ParamsBag;
use WP_Post;
use WP_Term_Query;

/**
 * Class PostQueryItem
 * @package tiFy\PostType\Query
 *
 * @deprecated Utiliser \tiFy\Wordpress\Query\QueryPost
 */
class PostQueryItem extends ParamsBag implements PostQueryItemContract
{
    /**
     * Objet Post Wordpress.
     * @var WP_Post
     */
    protected $object;

    /**
     * CONSTRUCTEUR.
     *
     * @param WP_Post $wp_post Objet Post Wordpress.
     *
     * @return void
     */
    public function __construct(WP_Post $wp_post)
    {
        $this->object = $wp_post;

        parent::__construct($this->object->to_array());
    }

    /**
     * @inheritDoc
     */
    public static function createFromId($post_id): ?PostQueryItemContract
    {
        return ($post_id && is_numeric($post_id) && ($wp_post = get_post($post_id)) && ($wp_post instanceof WP_Post))
            ? new static($wp_post) : null;
    }

    /**
     * @inheritDoc
     */
    public static function createFromPostdata(array $postdata): ?PostQueryItemContract
    {
        return isset($postdata['ID']) ? new static(new WP_Post((object)$postdata)) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorId()
    {
        return (int)$this->get('post_author', 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getContent($raw = false)
    {
        $content = (string)$this->get('post_content', '');

        if (!$raw) :
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
        endif;

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getDate($gmt = false)
    {
       return $gmt
           ? (string)$this->get('post_date_gmt', '')
           : (string)$this->get('post_date', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getEditLink()
    {
        return get_edit_post_link($this->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getExcerpt($raw = false)
    {
        if (!$excerpt = (string)$this->get('post_excerpt', '')) :
            $text = $this->get('post_content', '');

            // @see /wp-includes/post-template.php \get_the_excerpt()
            $text = strip_shortcodes($text);
            $text = apply_filters('the_content', $text);
            $text = str_replace(']]>', ']]&gt;', $text);

            $excerpt_length = apply_filters('excerpt_length', 55);
            $excerpt_more = apply_filters('excerpt_more', ' ' . '[&hellip;]');
            $excerpt = wp_trim_words($text, $excerpt_length, $excerpt_more);
        endif;

        return $raw ? $excerpt : ($excerpt ? apply_filters('get_the_excerpt', $excerpt) : '');
    }

    /**
     * {@inheritdoc}
     */
    public function getGuid()
    {
        return (string)$this->get('guid', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return (int)$this->get('ID', 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($meta_key, $single = false, $default = null)
    {
        return get_post_meta($this->getId(), $meta_key, $single) ? : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaMulti($meta_key, $default = null)
    {
        return $this->getMeta($meta_key, false, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaSingle($meta_key, $default = null)
    {
        return $this->getMeta($meta_key, true, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function getModified($gmt = false)
    {
        return $gmt
            ? (string)$this->get('post_modified_gmt', '')
            : (string)$this->get('post_modified', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getSlug();
    }

    /**
     * {@inheritdoc}
     */
    public function getParentId()
    {
        return (int)$this->get('post_parent', 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getPermalink()
    {
        return get_permalink($this->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getPost()
    {
        return $this->object;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
        return (string)$this->get('post_name', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return (string)$this->get('post_status', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getTerms($taxonomy, $args = [])
    {
        $args['taxonomy'] = $taxonomy;
        $args['object_ids'] = $this->getId();

        return (new WP_Term_Query($args))->terms;
    }

    /**
     * {@inheritdoc}
     */
    public function getThumbnail($size = 'post-thumbnail', $attrs = [])
    {
        return get_the_post_thumbnail($this->getId(), $size, $attrs);
    }

    /**
     * {@inheritdoc}
     */
    public function getThumbnailUrl($size = 'post-thumbnail')
    {
        return get_the_post_thumbnail_url($this->getId(), $size);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle($raw = false)
    {
        $title = (string)$this->get('post_title', '');

        return $raw ? $title : apply_filters('the_title', $title, $this->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return (string)$this->get('post_type', '');
    }
}