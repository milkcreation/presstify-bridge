<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager;

use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\FileManager\Contracts\Breadcrumb as BreadcrumbContract;
use tiFy\Support\Collection;

class Breadcrumb extends Collection implements BreadcrumbContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit associé.
     * @var FileManager
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->factory->viewer('breadcrumb', ['items' => $this]);
    }

    /**
     * @inheritDoc
     */
    public function setPath(?string $path = null): BreadcrumbContract
    {
        $file = $this->factory->getFile($path ?? $this->factory->getPath());
        $path = ($file->isDir()) ? $file->getRelPath() : $file->getDirname();

        $this->items = [];

        if ($path = ltrim($path, '/')) {
            $root = '';
            foreach (preg_split('#\/#', $path) as $name) {
                $root .= "/{$name}";
                $this->items[$root] = $name;
            }
        }

        return $this;
    }
}