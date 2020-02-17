<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager;

use tiFy\Support\Collection;
use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\FileManager\Contracts\{FileManager, FileCollection as FileCollectionContract, FileInfo};

class FileCollection extends Collection implements FileCollectionContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var FileManager
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->factory->viewer('files', ['files' => $this]);
    }

    /**
     * @inheritDoc
     */
    public function sortByDir(): FileCollectionContract
    {
        $dirs = $this->collect()
            ->filter(function(FileInfo $item) {
                return $item->isDir();
            })
            ->sortBy(function (FileInfo $item) {
                return $item->get('basename');
            })->all();

        $files = $this->collect()
            ->filter(function(FileInfo $item) {
                return $item->isFile();
            })
            ->sortBy(function (FileInfo $item) {
                return $item->get('basename');
            })->all();

        $this->items = $dirs;
        foreach($files as $file) {
            $this->items[] = $file;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function walk($value, $key = null)
    {
        return $this->items[$key] = $this->getFactory()->resolve('file-info', [$value]);
    }
}