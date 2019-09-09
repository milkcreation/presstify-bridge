<?php

namespace tiFy\Wordpress\Metabox;

use tiFy\Contracts\Metabox\MetaboxManager;

class Metabox
{
    /**
     * Instance du gestionnaire utilisateur.
     * @var MetaboxManager
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param MetaboxManager $manager Instance du gestionnaire de metaboxes.
     *
     * @return void
     */
    public function __construct(MetaboxManager $manager)
    {
        $this->manager = $manager;
    }
}