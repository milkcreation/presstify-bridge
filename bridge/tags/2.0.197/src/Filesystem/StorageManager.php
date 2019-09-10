<?php declare(strict_types=1);

namespace tiFy\Filesystem;

use InvalidArgumentException;
use League\Flysystem\{AdapterInterface,
    Cached\CachedAdapter,
    Cached\CacheInterface,
    Cached\Storage\Memory as MemoryStore,
    FilesystemInterface,
    FilesystemNotFoundException,
    MountManager};
use tiFy\Contracts\Container\Container;
use tiFy\Contracts\Filesystem\{
    Filesystem as FilesystemContract,
    LocalAdapter as LocalAdapterContract,
    LocalFilesystem as LocalFilesystemContract,
    StorageManager as StorageManagerContract};

class StorageManager extends MountManager implements StorageManagerContract
{
    /**
     * Instance du conteneur d'injection de dépendance.
     * @var Container
     */
    protected $container;

    /**
     * CONSTRUCTEUR.
     *
     * @param Container|null $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        $this->container = $container;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function disk(string $name): ?FilesystemContract
    {
        try {
            return $this->getFilesystem($name);
        } catch (FilesystemNotFoundException $e) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function getFilesystem($prefix)
    {
        return parent::getFilesystem($prefix);
    }

    /**
     * @inheritDoc
     */
    public function local(string $root, array $config = []): LocalFilesystemContract
    {
        return $this->getContainer() && $this->getContainer()->has(LocalFilesystemContract::class)
            ? $this->getContainer()->get(LocalFilesystemContract::class, [$root, $config])
            : new LocalFilesystem($this->localAdapter($root, $config));
    }

    /**
     * @inheritDoc
     */
    public function localAdapter(string $root, array $config = []): AdapterInterface
    {
        $permissions = $config['permissions'] ?? [];
        $links = ($config['links'] ?? null) === 'skip'
            ? LocalAdapter::SKIP_LINKS
            : LocalAdapter::DISALLOW_LINKS;

        $adapter = ($this->getContainer() && $this->getContainer()->has(LocalAdapterContract::class))
            ? $this->getContainer()->get(LocalAdapterContract::class, [$root, LOCK_EX, $links, $permissions])
            : new LocalAdapter($root, LOCK_EX, $links, $permissions);

        if ($cache = $config['cache'] ?? true) {
            $adapter = $cache instanceof CacheInterface
                ? new CachedAdapter($adapter, $cache)
                : new CachedAdapter($adapter, new MemoryStore());
        }

        return $adapter;
    }

    /**
     * @inheritDoc
     */
    public function mountFilesystem($name, FilesystemInterface $filesystem)
    {
        if ($filesystem instanceof FilesystemContract) {
            return parent::mountFilesystem($name, $filesystem);
        }
        throw new InvalidArgumentException(
            sprintf(
                __('Impossible de monter le disque %s. Le gestionnaire de fichiers doit une instance de %s.', 'tify'),
                $name,
                FilesystemContract::class
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function register(string $name, $attrs): ?FilesystemContract
    {
        if ($attrs instanceof Filesystem) {
            $filesystem = $attrs;
        } elseif (is_array($attrs)) {
            $filesystem = $this->local($attrs['root']?? '', $attrs);
        } elseif (is_string($attrs)) {
            $filesystem = $this->local($attrs);
        } else {
            throw new InvalidArgumentException(
                sprintf(
                    __('Les arguments fournis ne permettent pas de définir le système de fichiers %s', 'theme'),
                    $name
                )
            );
        }

        return $this->set($name, $filesystem)->disk($name);
    }

    /**
     * @inheritDoc
     */
    public function set(string $name, FilesystemContract $filesystem): StorageManagerContract
    {
        return $this->mountFilesystem($name, $filesystem);
    }
}