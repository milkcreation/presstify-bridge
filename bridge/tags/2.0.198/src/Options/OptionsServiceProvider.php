<?php

namespace tiFy\Options;

use tiFy\Container\ServiceProvider;

class OptionsServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'options',
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->getContainer()->share('options', function () {
            return new Options($this->getContainer());
        });
    }
}