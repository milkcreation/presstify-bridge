<?php declare(strict_types=1);

namespace tiFy\Template\Templates\UserListTable;

use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\UserListTable\Contracts\Db as DbContract;
use tiFy\Wordpress\Database\Model\User;

class Db extends User implements DbContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var Factory
     */
    protected $factory;
}