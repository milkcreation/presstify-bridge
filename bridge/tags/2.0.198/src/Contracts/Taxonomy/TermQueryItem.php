<?php

namespace tiFy\Contracts\Taxonomy;

use tiFy\Contracts\Kernel\ParamsBag;

/**
 * Interface TermQueryItem
 * @package tiFy\Contracts\Taxonomy
 *
 * @deprecated
 */
interface TermQueryItem extends ParamsBag
{
    /**
     * Récupération de la description.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Récupération de l'identifiant de qualification Wordpress du terme.
     *
     * @return int
     */
    public function getId();

    /**
     * Récupération d'une metadonnée.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param bool $single Type de metadonnés. single (true)|multiple (false). false par défaut.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMeta($meta_key, $single = false, $default = null);

    /**
     * Récupération d'une metadonnée de type multiple.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMetaMulti($meta_key, $default = null);

    /**
     * Récupération d'une metadonnée de type simple.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMetaSingle($meta_key, $default = null);

    /**
     * Récupération de l'intitulé de qualification.
     *
     * @return string
     */
    public function getName();

    /**
     * Récupération du nom de qualification Wordpress du terme.
     *
     * @return string
     */
    public function getSlug();

    /**
     * Récupération de la taxonomie relative.
     *
     * @return string
     */
    public function getTaxonomy();

    /**
     * Récupération de l'object Terme Wordpress associé.
     *
     * @return \WP_Term
     */
    public function getTerm();
}