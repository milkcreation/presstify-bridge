<?php declare(strict_types=1);

namespace tiFy\Log;

use App\App;
use tiFy\Container\ServiceProvider;
use tiFy\Support\Str;

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
    protected $provides = [
        'log',
        'logger'
    ];

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->getContainer()->share('log', function () {
            return new LogManager($this->getContainer()->get('app'));
        });

        $this->getContainer()->add('logger', function (?string $name = null) {
            return (new Logger($name ?? Str::random()))
                ->setContainer($this->getContainer()->get('app'));
        });
    }
}