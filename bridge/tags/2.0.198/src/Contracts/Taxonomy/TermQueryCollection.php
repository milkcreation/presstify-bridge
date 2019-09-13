<?php

namespace tiFy\Contracts\Taxonomy;

/**
 * Interface TermQueryCollection
 * @package tiFy\Contracts\Taxonomy
 *
 * @deprecated
 */
interface TermQueryCollection
{
    /**
     * Récupération de la liste des identifiants de qualification.
     *
     * @return array
     */
    public function getIds();

    /**
     * Récupération de la liste des identifiants de qualification.
     *
     * @return array
     */
    public function getNames();

    /**
     * Récupération de la liste des identifiants de qualification.
     *
     * @return array
     */
    public function getSlugs();
}