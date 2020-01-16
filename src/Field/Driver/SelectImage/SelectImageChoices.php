<?php declare(strict_types=1);

namespace tiFy\Field\Driver\SelectImage;

use Symfony\Component\Finder\Finder;
use tiFy\Contracts\Field\SelectImage;
use tiFy\Field\Driver\SelectJs\SelectJsChoices;
use tiFy\Support\Img;

class SelectImageChoices extends SelectJsChoices
{
    /**
     * CONSTRUCTEUR.
     *
     * @param array|string $items
     * @param mixed $selected Liste des éléments selectionnés
     * @param SelectImage $field
     *
     * @return void
     */
    public function __construct($items, $selected, SelectImage $field)
    {
        if (is_string($items)) {
            $finder = new Finder();
            $finder
                ->in($items)
                ->depth('== 0')
                ->files()
                ->name('/(\.ico$|\.gif$|\.jpe?g$|\.png$|\.svg$)/');

            $items = [];
            if ($field->get('none')) {
                $items[''] = Img::getBase64Src($field->manager()->resourcesDir('/views/select-image/none.jpg'));
            }

            foreach ($finder as $file) {
                $items[$file->getRelativePathname()] = Img::getBase64Src($file->getRealPath());
            }
        }

        parent::__construct($items, $selected);
    }
}