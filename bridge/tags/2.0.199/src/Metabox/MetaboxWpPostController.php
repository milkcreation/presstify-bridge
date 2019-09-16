<?php declare(strict_types=1);

namespace tiFy\Metabox;

use tiFy\Contracts\Metabox\{MetaboxController as MetaboxControllerContract,
    MetaboxWpPostController as MetaboxWpPostControllerContract};
use WP_Post;

abstract class MetaboxWpPostController extends MetaboxController implements MetaboxWpPostControllerContract
{
    /**
     * {@inheritDoc}
     *
     * @param WP_Post $post
     */
    public function content($post = null, $args = null, $null = null)
    {
        return parent::content($post, $args, $null);
    }

    /**
     * @inheritDoc
     */
    public function getPostType()
    {
        return $this->getObjectName();
    }

    /**
     * @inheritDoc
     */
    public function header($post = null, $args = null, $null = null)
    {
        return parent::header($post, $args, $null);
    }

    /**
     * @inheritDoc
     */
    public function metadatas()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function prepare(): MetaboxControllerContract
    {
        parent::prepare();

        foreach ($this->metadatas() as $meta => $single) {
            if (is_numeric($meta)) {
                $meta = (string)$single;
                $single = true;
            }

            post_type()->post_meta()->register($this->getPostType(), $meta, $single);
        }

        return $this;
    }
}