<?php

namespace tiFy\Wordpress\Filesystem;

use tiFy\Contracts\Filesystem\StorageManager;

class Filesystem
{
    /**
     * Instance du controleur du gestion de systèmes de fichier.
     * @var StorageManager
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param StorageManager $manager Instance du controleur du gestion de systèmes de fichier.
     *
     * @return void
     */
    public function __construct(StorageManager $manager)
    {
        $this->manager = $manager;

        foreach(config('filesystem', []) as $name => $attrs) {
            $this->manager->register($name, $attrs);
        }
    }
}