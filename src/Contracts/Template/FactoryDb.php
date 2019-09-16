<?php declare(strict_types=1);

namespace tiFy\Contracts\Template;

use tiFy\Contracts\Database\ColumnsAwareTrait;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
interface FactoryDb extends ColumnsAwareTrait, FactoryAwareTrait
{

}