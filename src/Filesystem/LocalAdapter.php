<?php declare(strict_types=1);

namespace tiFy\Filesystem;

use League\Flysystem\Adapter\Local;
use tiFy\Contracts\Filesystem\LocalAdapter as LocalAdapterContract;

class LocalAdapter extends Local implements LocalAdapterContract
{

}