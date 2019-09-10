<?php

namespace tiFy\Contracts\Db;

interface DbFactoryMake extends DbFactoryResolverTrait
{
    /**
     * Installation.
     *
     * @return void
     */
    public function install();
}