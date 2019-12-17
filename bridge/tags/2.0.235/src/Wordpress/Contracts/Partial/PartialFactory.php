<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Partial;

use tiFy\Contracts\Partial\PartialFactory as BasePartialFactory;

interface PartialFactory extends BasePartialFactory
{
    /**
     * Mise en file des scripts (Feuilles de styles CSS et scripts JS).
     *
     * @return static
     */
    public function enqueue(): PartialFactory;
}