<?php declare(strict_types=1);

namespace tiFy\Template\Templates\PostListTable\Contracts;

use tiFy\Contracts\Template\{FactoryAwareTrait, FactoryDb};

/**
 * @mixin \tiFy\Wordpress\Database\Model\Post
 */
interface Db extends FactoryAwareTrait, FactoryDb
{

}