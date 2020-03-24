<?php declare(strict_types=1);

namespace tiFy\View;

use tiFy\Container\ServiceProvider;
use tiFy\View\Engine\PlatesEngine;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'view',
        'view.engine.default',
        'view.engine.plates'
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('view', function () {
            return new View($this->getContainer());
        });

        $this->getContainer()->add('view.engine.default', function () {
            return $this->getContainer()->get('view.engine.plates');
        });

        $this->getContainer()->add('view.engine.plates', function () {
            return new PlatesEngine($this->getContainer()->get('view'));
        });
    }
}