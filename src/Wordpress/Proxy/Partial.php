<?php declare(strict_types=1);

namespace tiFy\Wordpress\Proxy;

use tiFy\Support\Proxy\Partial as BasePartial;
use tiFy\Wordpress\Contracts\Partial\PartialDriver;

/**
 * @method static PartialDriver|null get(string $name, array|string|null $id = null, array $attrs = [])
 */
class Partial extends BasePartial
{

}