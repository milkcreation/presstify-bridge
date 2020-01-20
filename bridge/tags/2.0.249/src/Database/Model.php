<?php declare(strict_types=1);

namespace tiFy\Database;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Builder;
use tiFy\Database\Concerns\{ColumnsAwareTrait, ConnectionAwareTrait};

/**
 * @mixin Builder
 */
abstract class Model extends BaseModel
{
    use ColumnsAwareTrait, ConnectionAwareTrait;
}