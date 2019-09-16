<?php declare(strict_types=1);

namespace tiFy\Console;

use Symfony\Component\Console\Input\InputOption;
use tiFy\Container\ServiceProvider;
use tiFy\Kernel\Application as App;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = ['console.application'];

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        register_shutdown_function(function () {
            /** @var App $app */
            $app = $this->getContainer()->get('app');

            if($app->runningInConsole()) {
                $this->getContainer()->get('console.application')->run();
            }
        });
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('console.application', function() {
            $app = new Application('presstiFy PHP CLI Console', '1.0.0');

            foreach (config('console.commands', []) as $k => $command) {
                if (is_numeric($k) && class_exists($command)) {
                    $command = $app->add(new $command());
                } elseif (class_exists($command)) {
                    $command = $app->add(new $command($k));
                }

                if (!$command->getDefinition()->hasOption('url')) {
                    $command->addOption(
                        'url',
                        null,
                        InputOption::VALUE_OPTIONAL,
                        'site url'
                    );
                }
            }

            return $app;
        });
    }
}