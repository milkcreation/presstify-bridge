<?php declare(strict_types=1);

namespace tiFy\Database;

use Illuminate\Database\Capsule\Manager as LaraDatabase;
use tiFy\Contracts\Database\Database as DatabaseContract;

class Database extends LaraDatabase implements DatabaseContract
{

}