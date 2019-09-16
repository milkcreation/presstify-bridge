<?php declare(strict_types=1);

namespace tiFy\Support;

use Psr\Container\ContainerInterface;
use tiFy\Contracts\Support\Manager as ManagerContract;

class Manager implements ManagerContract
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Liste des éléments déclarés
     * @var array
     */
    protected $items = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param ContainerInterface|null $container Conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?ContainerInterface $container = null)
    {
        $this->container = $container;

        $this->boot();
    }

    /**
     * @inheritDoc
     */
    public function all(): iterable
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function boot(): void {}

    /**
     * @inheritDoc
     */
    public function get(...$args)
    {
        return $this->items[$args[0]] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?ContainerInterface
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function register($key, ...$args)
    {
        return $this->set([$key => $args]);
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        array_walk($keys, [$this, 'walk']);

        foreach ($keys as $k => $i) {
            $this->items[$k] = $i;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function walk(&$item, $key = null): void {}
}