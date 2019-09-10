<?php declare(strict_types=1);

namespace tiFy\Console;

use Symfony\Component\Console\Input\InputOption;
use tiFy\Container\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = [
        'console.application'
    ];

    /**
     * @inheritDoc
     */
    public function boot()
    {
        add_action('shutdown', function () {
            /** @var \tiFy\Kernel\Application $app */
            $app = $this->getContainer()->get('app');

            if($app->runningInConsole()) {
                $this->getContainer()->get('console.application')->run();
            }
        }, 999999);
    }

    /**
     * @inheritDoc
     */
    public function register()
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