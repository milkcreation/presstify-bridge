<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Field;

use tiFy\Contracts\Field\FieldFactory as BaseFieldFactory;

interface FieldFactory extends BaseFieldFactory
{
    /**
     * Mise en file des scripts (Feuilles de styles CSS et scripts JS).
     *
     * @return static
     */
    public function enqueue(): FieldFactory;
}