<?php declare(strict_types=1);

namespace tiFy\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use tiFy\Database\Concerns\{
    ColumnsAwareTrait,
    ConnectionAwareTrait
};

/**
 * @mixin Builder
 */
abstract class AbstractModel extends Model
{
    use ColumnsAwareTrait, ConnectionAwareTrait;
}