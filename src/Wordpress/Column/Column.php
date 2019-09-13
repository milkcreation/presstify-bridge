<?php

namespace tiFy\Wordpress\Column;

use tiFy\Contracts\Column\Column as ColumnManager;

class Column
{
    /**
     * Instance du gestionnaire des colonnes.
     * @var ColumnManager
     */
    protected $manager;

    /**
     * CONSTRUCTEUR
     *
     * @param ColumnManager $manager Instance du gestionnaire des champs.
     *
     * @return void
     */
    public function __construct(ColumnManager $manager)
    {
        $this->manager = $manager;
    }
}