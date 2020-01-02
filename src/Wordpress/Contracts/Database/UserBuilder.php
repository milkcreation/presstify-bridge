<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Database;

use Corcel\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @see https://github.com/corcel/corcel#users
 *
 * @mixin Model
 * @mixin User
 * @mixin Builder
 */
interface UserBuilder
{

}