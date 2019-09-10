<?php

namespace tiFy\Taxonomy\Query;

use tiFy\Contracts\Taxonomy\TermQueryItem as TermQueryItemContract;
use tiFy\Kernel\Params\ParamsBag;

/**
 * Class TermQueryItem
 * @package tiFy\Taxonomy\Query
 *
 * @deprecated
 */
class TermQueryItem extends ParamsBag implements TermQueryItemContract
{
    /**
     * Objet Term Wordpress
     * @var \WP_Term
     */
    protected $object;

    /**
     * CONSTRUCTEUR.
     *
     * @param \WP_Term $wp_term
     *
     * @return void
     */
    public function __construct(\WP_Term $wp_term)
    {
        $this->object = $wp_term;

        parent::__construct($this->object->to_array());
    }

    /**
     * Récupération de la description.
     *
     * @return string
     */
    public function getDescription()
    {
        return (string)$this->get('description', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return (int)$this->get('term_id', 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($meta_key, $single = false, $default = null)
    {
        return get_term_meta($this->getId(), $meta_key, $single) ? : $default;
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
    public function getName()
    {
        return (string)$this->get('name', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
        return (string)$this->get('slug', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxonomy()
    {
        return (string)$this->get('taxonomy', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getTerm()
    {
        return $this->object;
    }
}