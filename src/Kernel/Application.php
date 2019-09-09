<?php

namespace tiFy\Kernel;

use BadMethodCallException;
use Exception;
use Interop\Container\ContainerInterface;
use tiFy\Container\Container;
use XStatic\ProxyManager;

class Application extends Container
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var ContainerInterface
     */
    protected $container;

    /**
     * CONSTRUCTEUR.
     *
     * @param ContainerInterface $container
     *
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->delegate($container);

        parent::__construct();

        $this->registerProxy();

        $this->boot();
    }

    /**
     * Délégation d'appel des méthodes du conteneur d'injection.
     *
     * @param string $name Nom de la méthode à appeler.
     * @param array $arguments Liste des variables passées en argument.
     *
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call($name, $arguments)
    {
        try {
            return $this->container->$name(...$arguments);
        } catch (Exception $e) {
            throw new BadMethodCallException(sprintf(__('La méthode %s n\'est pas disponible.', 'tify'), $name));
        }
    }

    /**
     * @inheritDoc
     */
    public function boot(): void {}

    /**
     * @inheritDoc
     */
    public function getServiceProviders(): array
    {
        return config('app.providers', []);
    }

    /**
     * Détermine si l'application est lancée dans une console.
     *
     * @return boolean
     */
    public function runningInConsole(): bool
    {
        global $argv;

        if (isset($_ENV['APP_RUNNING_IN_CONSOLE'])) {
            return $_ENV['APP_RUNNING_IN_CONSOLE'] === 'true';
        } elseif(isset($argv[0]) && preg_match('/vendor\/bin\/bee$/', $argv[0])) {
            return true;
        }
        return php_sapi_name() === 'cli' || php_sapi_name() === 'phpdbg';
    }

    /**
     *
     */
    public function registerProxy()
    {
        $manager = new ProxyManager($this);
        foreach(config('app.proxy', []) as $alias => $proxy) {
            $manager->addProxy($alias, $proxy);
        }
        $manager->enable(ProxyManager::ROOT_NAMESPACE_ANY);
    }

    /**
     * Compatibilité corcel.
     *
     * @return string
     */
    public function version(): string
    {
        return 'tiFy';
    }
}