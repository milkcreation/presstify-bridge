<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Filesystem\{StorageManager, Filesystem, ImgAdapter, ImgFilesystem, LocalAdapter, LocalFilesystem};

/**
 * @method static Filesystem|null disk(string|null $name = null)
 * @method static ImgFilesystem img(string|ImgAdapter $root, array $config = [])
 * @method static ImgAdapter imgAdapter(string $root, array $config = [])
 * @method static LocalFilesystem local(string|LocalAdapter $root, array $config = [])
 * @method static LocalAdapter localAdapter(string $root, array $config = [])
 * @method static Filesystem|null register(string $name, string|array|Filesystem $attrs)
 * @method static ImgFilesystem|null registerImg(string $name, string|array|ImgFilesystem $attrs)
 * @method static LocalFilesystem|null registerLocal(string $name, string|array|LocalFilesystem $attrs)
 * @method static Filesystem|null system(string|null $name = null)
 */
class Storage extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return StorageManager
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @inheritDoc
     */
    public static function getInstanceIdentifier()
    {
        return 'storage';
    }
}