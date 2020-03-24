<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager;

use tiFy\Template\Factory\View as BaseView;
use tiFy\Template\Templates\FileManager\Contracts\{Breadcrumb, FileCollection, FileInfo};

/**
 * @method Breadcrumb|iterable breadcrumb()
 * @method FileInfo|null getFile(?string $path = null)
 * @method FileCollection|FileInfo[] getFiles(?string $path = null, bool $recursive = false)
 * @method string getIcon(string $name = null, ...$args)
 * @method string preview(FileInfo $file)
 */
class View extends BaseView
{
    /**
     * Instance du gabarit associé.
     * @var FileManager
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function boot(): void
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