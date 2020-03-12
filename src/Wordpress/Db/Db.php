<?php

namespace tiFy\Wordpress\Db;

use tiFy\Contracts\Db\DbManager;
use tiFy\Wordpress\Contracts\Db as DbContract;

class Db implements DbContract
{
    /**
     * Instance du controleur de gestion des bases de données.
     * @var DbManager
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param DbManager $manager Instance du controleur des bases de données.
     *
     * @return void
     */
    public function __construct(DbManager $manager)
    {
        $this->manager = $manager;

        add_action('init', function () {
            foreach(config('db', []) as $name => $attrs) {
                $this->manager->register($name, $attrs);
            }
        }, 9);
    }
}