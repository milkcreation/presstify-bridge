<?php declare(strict_types=1);

namespace tiFy\Log;

use App\App;
use tiFy\Container\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{
    /**
     * Instance de l'application.
     * @var App|null
     */
    protected $app;

    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = ['log'];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('log', function () {
            return new LogManager($this->getContainer()->get('app'));
        });
    }
}