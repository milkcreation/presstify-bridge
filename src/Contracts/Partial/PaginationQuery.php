<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Support\Traits\PaginationAwareTrait;

/**
 * @mixin PaginationAwareTrait
 */
interface PaginationQuery extends ParamsBag
{
}