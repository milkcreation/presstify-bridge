<?php declare(strict_types=1);

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
        'options.page',
        'options.page.viewer',
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('options', function () {
            return new Options($this->getContainer());
        });
    }
}