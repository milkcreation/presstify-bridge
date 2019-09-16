<?php

namespace tiFy\Metabox;

use tiFy\Container\ServiceProvider;
use tiFy\Metabox\Tab\MetaboxTabController;

class MetaboxServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet un chargement différé des services.}
     * @var string[]
     */
    protected $provides = [
        'metabox',
        'metabox.factory',
        'metabox.tab'
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->getContainer()->share('metabox', function () {
            return new MetaboxManager();
        })->build();

        $this->getContainer()->add('metabox.factory', function ($name, $attrs = [], $screen = null) {
            return new MetaboxFactory($name, $attrs, $screen);
        });

        $this->getContainer()->add('metabox.tab', function ($attrs = [], $screen = null) {
            return new MetaboxTabController($attrs, $screen);
        });
    }
}