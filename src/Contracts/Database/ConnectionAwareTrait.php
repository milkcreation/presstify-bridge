<?php declare(strict_types=1);

namespace tiFy\Contracts\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Model
 * @mixin Builder
 */
interface ConnectionAwareTrait
{
    /**
     * Récupération du prefixe des tables.
     *
     * @return string
     */
    public function getTablePrefix(): string;
}