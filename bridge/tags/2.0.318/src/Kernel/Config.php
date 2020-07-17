<?php declare(strict_types=1);

namespace tiFy\Kernel;

use Psr\Container\ContainerInterface as Container;
use Symfony\Component\Finder\{Finder, SplFileInfo};
use tiFy\Contracts\Kernel\{Config as ConfigContract, Path};
use tiFy\Support\ParamsBag;

class Config extends ParamsBag implements ConfigContract
{
    /**
     * Instancedu conteneur d'injection de dépendances.
     * @var Container
     */
    protected $container;

    /**
     * Classe de rappel du controleur des chemins.
     * @var Path
     */
    protected $paths;

    /**
     * CONSTRUCTEUR.
     *
     * @param Container $container
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->paths = $this->container->get('path');

        if (file_exists($this->paths->getConfigPath('autoload.php'))) {
            $autoloads = include $this->paths->getConfigPath('autoload.php');
            foreach ($autoloads as $type => $autoload) {
                foreach ($autoload as $namespace => $path) {
                    $this->container->get('class-loader')->load($namespace, $path, $type);
                }
            }
        }

        $attrs = [];
        if (is_dir(paths()->getConfigPath())) {
            $finder = (new Finder())->files()->name('/\.php$/')->in(paths()->getConfigPath());
            foreach ($finder as $file) {
                /* @var SplFileInfo $file */
                $key = basename($file->getFilename(), ".{$file->getExtension()}");
                if ($key === 'autoload') {
                    continue;
                }
                $attrs[$key] = include($file->getRealPath());
            }
        }
        $this->set($attrs)->parse();
    }

    /**
     * @inheritDoc
     */
    public function defaults()
    {
        return [
            'app_url' => env('APP_URL')
        ];
    }
}