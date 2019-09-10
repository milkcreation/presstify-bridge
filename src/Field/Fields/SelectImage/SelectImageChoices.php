<?php

namespace tiFy\Field\Fields\SelectImage;

use Symfony\Component\Finder\Finder;
use tiFy\Field\Fields\SelectJs\SelectJsChoices;
use tiFy\Kernel\Tools;

class SelectImageChoices extends SelectJsChoices
{
    /**
     * CONSTRUCTEUR.
     *
     * @param array|string $items
     * @param mixed $selected Liste des éléments selectionnés
     */
    public function __construct($items, $selected = null)
    {
        if (is_string($items)) {
            $finder = new Finder();
            $finder
                ->in($items)
                ->depth('== 0')
                ->files()
                ->name('#(\.ico$|\.gif$|\.jpe?g$|\.png$|\.svg$)#');
            $items = [];
            foreach ($finder as $file) {
                $items[$file->getRelativePathname()] = Tools::File()->imgBase64Src($file->getRealPath());
            }
        }

        parent::__construct($items, $selected);
    }
}