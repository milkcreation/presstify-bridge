<?php declare(strict_types=1);

namespace tiFy\Kernel;

use Composer\Autoload\ClassLoader as ComposerClassLoader;
use Psr\Container\ContainerInterface;
use tiFy\Contracts\Kernel\ClassLoader as ClassLoaderContract;

class ClassLoader extends ComposerClassLoader implements ClassLoaderContract
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var ContainerInterface
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
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->paths = $this->container->get('path');
    }

    /**
     * @inheritdoc
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