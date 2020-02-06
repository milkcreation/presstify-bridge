<?php

namespace tiFy\Wordpress\Options;

use tiFy\Options\Options as OptionsManager;

class Options
{
    /**
     * Instance du gestionnaire des champs.
     * @var OptionsManager
     */
    protected $manager;

    /**
     * CONSTRUCTEUR
     *
     * @param OptionsManager $manager Instance du gestionnaire des options.
     *
     * @return void
     */
    public function __construct(OptionsManager $manager)
    {
        $this->manager = $manager;
    }
}