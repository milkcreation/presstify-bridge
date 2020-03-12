<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager;

use Exception;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Util;
use tiFy\Contracts\Filesystem\Filesystem;
use tiFy\Contracts\Template\{TemplateFactory as TemplateFactoryContract};
use tiFy\Template\TemplateFactory;
use tiFy\Template\Templates\FileManager\Contracts\{
    Ajax,
    Breadcrumb,
    FileInfo,
    FileManager as FileManagerContract,
    FileCollection,
    IconSet,
    Sidebar};

class FileManager extends TemplateFactory implements FileManagerContract
{
    /**
     * Liste des fournisseurs de service.
     * @var string[]
     */
    protected $serviceProviders = [
        FileManagerServiceProvider::class
    ];

    /**
     * Chemin d'accès courant aux fichier.
     * @var string
     */
    protected $path = '/';

    /**
     * @inheritDoc
     */
    public function adapter(): AdapterInterface
    {
        return $this->filesystem()->getAdapter();
    }

    /**
     * @inheritDoc
     */
    public function ajax(): Ajax
    {
        return $this->resolve('ajax');
    }

    /**
     * @inheritDoc
     */
    public function breadcrumb(): Breadcrumb
    {
        return $this->resolve('breadcrumb');
    }

    /**
     * @inheritDoc
     */
    public function filesystem(): Filesystem
    {
        return $this->resolve('filesystem');
    }

    /**
     * @inheritDoc
     */
    public function fileinfo(array $datas): Fileinfo
    {
        return $this->resolve('file-info', [$datas]);
    }

    /**
     * @inheritDoc
     */
    public function getFile(?string $path = null): ?Fileinfo
    {
        $path = $path ?? $this->getPath();

        try {
            $adapter = $this->filesystem()->getRealAdapter();

            return $this->fileinfo($adapter->getMetadata($path) + Util::pathinfo($path));
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function getFiles(?string $path = null, bool $recursive = false): ?FileCollection
    {
        $file = $this->getFile($path ?? $this->getPath());

        $collection = $this->resolve(
            'file-collection',
            [$this->filesystem()->listContents($file->isDir() ? $file->getRelPath() : $file->getDirname(), $recursive)]
        );

        return $collection->sortByDir();
    }

    /**
     * @inheritDoc
     */
    public function getIcon($name, ...$args): ?string
    {
        return $this->icon()->$name(...$args);
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path ?: '/';
    }

    /**
     * @inheritDoc
     */
    public function icon(): IconSet
    {
        return $this->resolve('icon-set');
    }

    /**
     * {@inheritDoc}
     *
     * @return FileManagerContract
     */
    public function prepare(): TemplateFactoryContract
    {
        if (!$this->prepared) {
            parent::prepare();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function preview(FileInfo $file): string
    {
        if ($this->viewer()->exists("preview-{$file->getExtension()}")) {
            return $this->viewer("preview-{$file->getExtension()}", compact('file'));
        }

        foreach ($file->tag()->get() as $tag) {
            if ($this->viewer()->exists("preview-{$tag}")) {
                return $this->viewer("preview-{$tag}", compact('file'));
            }
        }

        return $this->viewer('preview', compact('file'));
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        return $this->viewer('file-manager');
    }

    /**
     * @inheritDoc
     */
    public function sidebar(): Sidebar
    {
        return $this->resolve('sidebar');
    }

    /**
     * @inheritDoc
     */
    public function setPath(string $path): FileManagerContract
    {
        $this->path = $path;

        return $this;
    }
}