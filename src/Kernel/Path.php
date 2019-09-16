<?php declare(strict_types=1);

namespace tiFy\Kernel;

use League\Flysystem\{
    Cached\CachedAdapter,
    Cached\CacheInterface,
    Cached\Storage\Memory as MemoryStore,
    FilesystemNotFoundException};
use tiFy\Contracts\Filesystem\LocalFilesystem as LocalFilesystemContract;
use tiFy\Contracts\Kernel\Path as PathContract;
use tiFy\Filesystem\{LocalAdapter, LocalFilesystem,  StorageManager};

class Path extends StorageManager implements PathContract
{
    /**
     * Séparateur de dossier.
     * @var string
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     * @inheritDoc
     */
    public function diskBase(): LocalFilesystemContract
    {
        if (!$disk = $this->getFilesystem('base')) {
            $disk = $this->mount('base', ROOT_PATH);
        }

        return $disk;
    }

    /**
     * @inheritDoc
     */
    public function diskCache(): LocalFilesystemContract
    {
        if (!$disk = $this->getFilesystem('cache')) {
            $disk = $this->mount('cache', $this->getStoragePath('/cache'));
        }

        return $disk;
    }

    /**
     * @inheritDoc
     */
    public function diskConfig(): LocalFilesystemContract
    {
        if (!$disk = $this->getFilesystem('config')) {
            $disk = $this->mount('config', !$this->isWp()
                ? $this->getBasePath('config') : get_template_directory() . '/config'
            );
        }

        return $disk;
    }

    /**
     * @inheritDoc
     */
    public function diskLog(): LocalFilesystemContract
    {
        if (!$disk = $this->getFilesystem('log')) {
            $disk = $this->mount('log', $this->getStoragePath('/log'));
        }

        return $disk;
    }

    /**
     * @inheritDoc
     */
    public function diskPathFromBase(LocalFilesystemContract $disk, string $path = '', bool $absolute = true): ?string
    {
        $path = preg_replace('#^' . preg_quote($this->getBasePath(), self::DS) . "#", '', $disk->path($path), 1, $n);

        return $n === 1 ? $this->getBasePath($path, $absolute) : null;
    }

    /**
     * @inheritDoc
     */
    public function diskPublic(): LocalFilesystemContract
    {
        if (!$disk = $this->getFilesystem('public')) {
            $disk = $this->mount('public', !$this->isWp() ? $this->getBasePath('/public') : ABSPATH);
        }

        return $disk;
    }

    /**
     * @inheritDoc
     */
    public function diskStorage(): LocalFilesystemContract
    {
        if (!$disk = $this->getFilesystem('storage')) {
            $disk = $this->mount('storage', !$this->isWp()
                ? $this->getBasePath('storage') : WP_CONTENT_DIR . '/uploads'
            );
        }

        return $disk;
    }

    /**
     * @inheritDoc
     */
    public function diskTheme(): LocalFilesystemContract
    {
        if (!$disk = $this->getFilesystem('theme')) {
            $disk = $this->mount('theme', get_template_directory());
        }

        return $disk;
    }

    /**
     * @inheritDoc
     */
    public function diskTiFy(): LocalFilesystemContract
    {
        if (!$disk = $this->getFilesystem('tify')) {
            $disk = $this->mount('tify', $this->getBasePath('/vendor/presstify/framework/src'));
        }

        return $disk;
    }

    /**
     * @inheritDoc
     */
    public function getBasePath(string $path = '', bool $absolute = true): string
    {
        return $this->normalize($absolute ? $this->diskBase()->path($path) : $path);
    }

    /**
     * @inheritDoc
     */
    public function getCachePath(string $path = '', bool $absolute = true): string
    {
        return $this->diskPathFromBase($this->diskCache(), $path, $absolute);
    }

    /**
     * @inheritDoc
     */
    public function getConfigPath(string $path = '', bool $absolute = true): string
    {
        return $this->diskPathFromBase($this->diskConfig(), $path, $absolute);
    }

    /**
     * {@inheritDoc}
     *
     * @return LocalFilesystem
     */
    public function getFilesystem($prefix): ?LocalFilesystemContract
    {
        try {
            /** @var LocalFilesystem $filesystem */
            $filesystem = parent::getFilesystem($prefix);
            return $filesystem;
        } catch (FilesystemNotFoundException $e) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function getLogPath(string $path = '', bool $absolute = true): string
    {
        return $this->diskPathFromBase($this->diskLog(), $path, $absolute);
    }

    /**
     * @inheritDoc
     */
    public function getPublicPath(string $path = '', bool $absolute = true): string
    {
        return $this->diskPathFromBase($this->diskPublic(), $path, $absolute);
    }

    /**
     * @inheritDoc
     */
    public function getStoragePath(string $path = '', bool $absolute = true): string
    {
        return $this->diskPathFromBase($this->diskStorage(), $path, $absolute);
    }

    /**
     * @inheritDoc
     */
    public function getThemePath(string $path = '', bool $absolute = true): string
    {
        return $this->diskPathFromBase($this->diskTheme(), $path, $absolute);
    }

    /**
     * @inheritDoc
     */
    public function getTifyPath(string $path = '', bool $absolute = true): string
    {
        return $this->diskPathFromBase($this->diskTiFy(), $path, $absolute);
    }

    /**
     * @inheritDoc
     */
    public function isWp(): bool
    {
        return defined('ABSPATH') && ($this->normalize(ABSPATH) === $this->normalize($this->getBasePath()));
    }

    /**
     * @inheritDoc
     */
    public function mount(string $name, string $root, array $config = []): LocalFilesystemContract
    {
        // @todo Utiliser le conteneur d'injection de dépendance.
        $permissions = $config['permissions'] ?? [];
        $links = ($config['links'] ?? null) === 'skip'
            ? LocalAdapter::SKIP_LINKS
            : LocalAdapter::DISALLOW_LINKS;

        $adapter = new LocalAdapter($root, LOCK_EX, $links, $permissions);

        if ($cache = $config['cache'] ?? true) {
            $adapter = $cache instanceof CacheInterface
                ? new CachedAdapter($adapter, $cache)
                : new CachedAdapter($adapter, new MemoryStore());
        }

        $filesystem = new LocalFilesystem($adapter, [
            'disable_asserts' => true,
            'case_sensitive' => true
        ]);
        $this->set($name, $filesystem);

        return $filesystem;
    }

    /**
     * @inheritDoc
     */
    public function normalize($path): string
    {
        return self::DS . ltrim(rtrim($path, self::DS), self::DS);
    }

    /**
     * @inheritDoc
     */
    public function relPathFromBase(string $pathname): ?string
    {
        $path = preg_replace('#^' . preg_quote($this->getBasePath(), self::DS) . "#", '', $pathname, 1, $n);

        return $n === 1 ? $this->getBasePath($path, false): null;
    }
}