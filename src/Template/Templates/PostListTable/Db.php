<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable;

use tiFy\Template\Factory\FactoryAwareTrait;
use tiFy\Template\Templates\PostListTable\Contracts\Db as DbContract;
use tiFy\Wordpress\Database\Model\Post;

class Db extends Post implements DbContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var Factory
     */
    protected $factory;
}