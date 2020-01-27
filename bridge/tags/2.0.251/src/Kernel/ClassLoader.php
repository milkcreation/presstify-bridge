<?php declare(strict_types=1);

namespace tiFy\Kernel;

use Composer\Autoload\ClassLoader as ComposerClassLoader;
use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Kernel\ClassLoader as ClassLoaderContract;

class ClassLoader extends ComposerClassLoader implements ClassLoaderContract
{
    /**
     * Instance du conteneur d'injection de dépendances.
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
     * @param Container $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->paths = $this->container->get('path');
    }

    /**
     * @inheritDoc
     */
    public function load(string $prefix, $paths, string $type = 'psr-4'): ClassLoaderContract
    {
        switch ($type) {
            default :
            case 'psr-4' :
                $this->addPsr4($prefix, $paths);
                break;
            case 'psr-0' :
                $this->add($prefix, $paths);
                break;
            case 'files' :
                if (is_string($paths)) {
                    $paths = (array)$paths;
                }
                foreach ($paths as $path) {
                    include_once $this->paths->getBasePath($path);
                }
                break;
            case 'classmap' :
                /** @todo */
                break;
        }

        $this->register();

        return $this;
    }
}