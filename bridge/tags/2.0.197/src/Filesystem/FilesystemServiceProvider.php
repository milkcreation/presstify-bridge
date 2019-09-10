<?php declare(strict_types=1);

namespace tiFy\Filesystem;

use League\Flysystem\AdapterInterface;
use tiFy\Container\ServiceProvider;
use tiFy\Contracts\Filesystem\{
    Filesystem as FilesystemContract,
    LocalFilesystem as LocalFilesystemContract,
    LocalAdapter as LocalAdapterContract};

class FilesystemServiceProvider extends ServiceProvider
{
    /**
     * Liste des services fournis.
     * @var array
     */
    protected $provides = [
        FilesystemContract::class,
        LocalAdapterContract::class,
        LocalFilesystemContract::class,
        'storage'
    ];

    /**
     * @inheritDoc
     */
    public function register():void
    {
        $this->getContainer()->share('storage', function () {
            return new StorageManager($this->getContainer());
        });

        $this->registerAdapter();
        $this->registerFilesystem();
    }

    /**
     * @inheritDoc
     */
    public function registerAdapter():void
    {
        $this->getContainer()->add(
            LocalAdapterContract::class,
            function (string $root, int $writeFlags, int $linkHandling, array $permissions = []) {
                return new LocalAdapter($root, $writeFlags, $linkHandling, $permissions);
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function registerFilesystem():void
    {
        $this->getContainer()->add(FilesystemContract::class, function (AdapterInterface $adapter) {
            return new Filesystem($adapter);
        });

        $this->getContainer()->add(LocalFilesystemContract::class, function (string $root, array $config = []) {
            return new LocalFilesystem($this->getContainer()->get('storage')->localAdapter($root, $config), [
                'disable_asserts' => true,
                'case_sensitive' => true
            ]);
        });
    }
}