<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager;

use tiFy\Template\Factory\FactoryViewer;
use tiFy\Template\Templates\FileManager\Contracts\{Breadcrumb, FileCollection, FileInfo};

/**
 * Class Viewer
 * @package tiFy\Template\Templates\FileManager
 *
 * @method Breadcrumb|iterable breadcrumb()
 * @method FileInfo|null getFile(?string $path = null)
 * @method FileCollection|FileInfo[] getFiles(?string $path = null, bool $recursive = false)
 * @method string getIcon(string $name = null, ...$args)
 * @method string preview(FileInfo $file)
 */
class Viewer extends FactoryViewer
{
    /**
     * Instance du gabarit associé.
     * @var FileManager
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function boot()
    {
        parent::boot();

        array_push(
            $this->mixins,
            'breadcrumb',
            'getFile',
            'getFiles',
            'getIcon',
            'param',
            'preview'
        );
    }
}