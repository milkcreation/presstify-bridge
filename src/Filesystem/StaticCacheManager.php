<?php declare(strict_types=1);

namespace tiFy\Filesystem;

use League\Flysystem\FileNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use tiFy\Contracts\Container\Container;
use tiFy\Contracts\Filesystem\{Filesystem, LocalFilesystem, StaticCacheManager as StaticCacheManagerContract};

class StaticCacheManager extends StorageManager implements StaticCacheManagerContract
{
    /**
     * Indicateur de conservation de l'extension d'un fichier mis en cache.
     * @var boolean
     */
    protected $withExtension = true;

    /**
     * CONSTRUCTEUR.
     *
     * @param Container|null $container Instance du conteneur d'injection de dépendances.
     * @param string| $cache_dir Chemin relatif vers le répertoire de stockage du cache.
     *
     * @return void
     */
    public function __construct(?Container $container = null, ?string $cache_dir = null)
    {
        parent::__construct($container);

        if ($cache_dir) {
            $path = ROOT_PATH . $cache_dir;
            $this->setCache($this->local($path));
        }
    }

    /**
     * @inheritDoc
     */
    public function cacheFileExists(string $path, array $params): bool
    {
        return ($path = $this->getCachePath($path, $params)) ? $this->getCache()->has($path) : false;
    }

    /**
     * @inheritDoc
     */
    public function clearCache(string $path = ''): void
    {
        if ($path) {
            $this->getCache()->deleteDir(dirname($this->getCachePath($path)));
        } else {
            foreach($this->getCache()->listContents() as $content) {
                if ($content['type'] === 'dir') {
                    $this->getCache()->deleteDir($content['path']);
                } else {
                    $this->getCache()->delete($content['path']);
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getResponse(string $path, ServerRequestInterface $psrRequest)
    {
        $sourcePath = $this->getSourcePath($path);
        $cachePath = $this->getCachePath($path, []);

        try {
            if (!$this->cacheFileExists($path, [])) {
                $this->put("cache://{$cachePath}", $this->read("source://{$sourcePath}"));
            }

            return $this->getCache()->binary($cachePath);
        } catch (FileNotFoundException $e) {
            return __('Impossible de retrouver le média.', 'tify');
        }
    }

    /**
     * @inheritDoc
     */
    public function getCache(): ?Filesystem
    {
        return $this->disk('cache');
    }

    /**
     * @inheritDoc
     */
    public function getCachePath(string $path, array $params =  []): ?string
    {
        $sourcePath = $this->getSourcePath($path);
        ksort($params);
        $md5 = md5($sourcePath . '?' . http_build_query($params));

        if ($this->withExtension) {
            $md5 .= ($ext = pathinfo($path, PATHINFO_EXTENSION)) ? ".{$ext}" : '';
        }

        return $sourcePath . '/' . $md5;
    }

    /**
     * @inheritDoc
     */
    public function getSource(): ?Filesystem
    {
        return $this->disk('source');
    }

    /**
     * @inheritDoc
     */
    public function getSourcePath(string $path): ?string
    {
        return rawurldecode($path);
    }

    /**
     * @inheritDoc
     */
    public function ready(): bool
    {
        return $this->getCache() && $this->getSource();
    }

    /**
     * @inheritDoc
     */
    public function setCache(LocalFilesystem $cache): StaticCacheManagerContract
    {
        $this->mountFilesystem('cache', $cache);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSource(Filesystem $source): StaticCacheManagerContract
    {
        $this->mountFilesystem('source', $source);

        return $this;
    }
}