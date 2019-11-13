<?php declare(strict_types=1);

namespace tiFy\Cache;

use InvalidArgumentException;
use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Cache\Store;
use tiFy\Support\Proxy\Database;

class Cache
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container
     */
    protected $container;

    /**
     * Liste des instances de gestion de cache.
     * @var Store[]
     */
    protected $stores = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param Container|null $container Conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        $this->container = $container;
    }

    /**
     * Délégation d'appel de l'instance de gestion de cache par défaut
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->store()->$method(...$parameters);
    }

    /**
     * Création d'une instance de gestion de cache en base de données.
     *
     * @param array $config Liste des attributs de configuration.
     *
     * @return Store
     */
    protected function createDatabaseStore(array $config): Store
    {
        $connection = Database::getInstance()->getConnection();

        return (new DatabaseStore($connection))
            ->setTable($config['table'] ?? null)
            ->setPrefix((string)($config['prefix'] ?? config('cache.prefix', '')));
    }

    /**
     * Récupération d'une instance de gestion de cache déclarée.
     *
     * @param string|null $name Nom de qualification de l'instance. Si null, retourne l'instance par défaut.
     *
     * @return Store
     */
    public function store(?string $name = null): Store
    {
        $name = $name ?? $this->getDefaultStore();

        return $this->stores[$name] = $this->stores[$name] ?? $this->resolve($name);
    }

    /**
     * Récupération du conteneur d'injection de dépendances.
     *
     * @return Container
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * Récupération du nom de qualification du gestionnaire de cache par défaut.
     *
     * @return string
     */
    public function getDefaultStore(): string
    {
        return (string)config('cache.default', 'database');
    }

    /**
     * Récupération de l'instance d'un gestionnaire de cache.
     *
     * @param  string  $name
     *
     * @return Store
     *
     * @throws InvalidArgumentException
     */
    protected function resolve(string $name)
    {
        $config = config("cache.stores.{$name}");

        if (is_null($config)) {
            throw new InvalidArgumentException(
                __("Le gestionnaire de cache [{$name}] n\'est pas déclaré.", 'tify')
            );
        }

        $store = $config['store'] ?? $name;

        $createMethod = 'create'.ucfirst($config['store'] ?? $name).'Store';
        if (method_exists($this, $createMethod)) {
            return $this->{$createMethod}($config);
        } else {
            throw new InvalidArgumentException(
                "Le gestionnaire de cache [{$store}] n\'est pas supporté."
            );
        }
    }
}