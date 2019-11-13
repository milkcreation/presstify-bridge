<?php declare(strict_types=1);

namespace tiFy\Contracts\Metabox;

use tiFy\Contracts\View\ViewController;

/**
 * @method string name()
 * @method mixed params(string|array|null $key = null, mixed $default = null)
 * @method mixed value(string|null $key = null, mixed $default = null)
 */
interface MetaboxView extends ViewController
{

}