<?php declare(strict_types=1);

namespace tiFy\View;

use tiFy\Container\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'viewer'
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->getContainer()->add('viewer', function () {
            return new ViewEngine([]);
        });
    }
}