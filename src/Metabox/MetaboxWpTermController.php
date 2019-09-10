<?php declare(strict_types=1);

namespace tiFy\Metabox;

use tiFy\Contracts\Metabox\{MetaboxController as MetaboxControllerContract,
    MetaboxWpTermController as MetaboxWpTermControllerContract};
use WP_Term;

abstract class MetaboxWpTermController extends MetaboxController implements MetaboxWpTermControllerContract
{
    /**
     * {@inheritDoc}
     *
     * @param WP_Term $term
     */
    public function content($term = null, $taxonomy = null, $args = null)
    {
        return parent::content($term, $taxonomy, $args);
    }

    /**
     * @inheritDoc
     */
    public function getTaxonomy()
    {
        return $this->getObjectName();
    }

    /**
     * @inheritDoc
     */
    public function header($term = null, $taxonomy = null, $args = null)
    {
        return parent::header($term, $taxonomy, $args);
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
    public function prepare():  MetaboxControllerContract
    {
        parent::prepare();

        foreach ($this->metadatas() as $meta => $single) {
            if (is_numeric($meta)) {
                $meta = (string)$single;
                $single = true;
            }

            taxonomy()->term_meta()->register($this->getTaxonomy(), $meta, $single);
        }

        return $this;
    }
}