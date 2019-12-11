<?php declare(strict_types=1);

namespace tiFy\Template\Templates\UserListTable\Contracts;

use tiFy\Contracts\Template\{FactoryAwareTrait, FactoryDb};

/**
 * @mixin \tiFy\Wordpress\Database\Model\User
 */
interface Db extends FactoryAwareTrait, FactoryDb
{

}