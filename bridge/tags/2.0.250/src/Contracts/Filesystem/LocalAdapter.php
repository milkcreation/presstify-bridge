<?php declare(strict_types=1);

namespace tiFy\Contracts\Filesystem;

use League\Flysystem\{AdapterInterface, Adapter\Local};

/**
 * @mixin Local
 */
interface LocalAdapter extends AdapterInterface
{

}