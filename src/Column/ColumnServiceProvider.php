<?php

namespace tiFy\Column;

use tiFy\Container\ServiceProvider;

class ColumnServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'column',
        'column.item'
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->getContainer()->share('column', function () {
            return new Column();
        });

        $this->getContainer()->add('column.item', function ($name, $attrs = [], $screen = null) {
            return new ColumnItemController($name, $attrs, $screen);
        });
    }
}