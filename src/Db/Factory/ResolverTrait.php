<?php

namespace tiFy\Db\Factory;

use tiFy\Contracts\Db\DbFactory;
use tiFy\Contracts\Db\DbFactoryHandle;
use tiFy\Contracts\Db\DbFactoryMake;
use tiFy\Contracts\Db\DbFactoryMeta;
use tiFy\Contracts\Db\DbFactoryMetaQuery;
use tiFy\Contracts\Db\DbFactoryParser;
use tiFy\Contracts\Db\DbFactoryQueryLoop;
use tiFy\Contracts\Db\DbFactorySelect;

trait ResolverTrait
{
    /**
     * Instance du controleur de base de données associé.
     * @var DbFactory
     */
    protected $db;

    /**
     * @inheritdoc
     */
    public function db()
    {
        return $this->db;
    }

    /**
     * @inheritdoc
     *
     * @return DbFactoryHandle
     */
    public function handle()
    {
        return $this->resolve('factory.handle', [$this->db]);
    }

    /**
     * @inheritdoc
     *
     * @return DbFactoryMake
     */
    public function make()
    {
        return $this->resolve('factory.make', [$this->db]);
    }

    /**
     * @inheritdoc
     *
     * @return DbFactoryMeta
     */
    public function meta()
    {
        return $this->resolve('factory.meta', [$this->db]);
    }

    /**
     * @inheritdoc
     *
     * @return DbFactoryMetaQuery
     */
    public function meta_query($query)
    {
        return $this->resolve('factory.meta-query', [$query, $this->db]);
    }

    /**
     * @inheritdoc
     *
     * @return DbFactoryParser
     */
    public function parser()
    {
        return $this->resolve('factory.parser', [$this->db]);
    }

    /**
     * @inheritdoc
     *
     * @return mixed
     */
    public function resolve($alias, $args = [])
    {
        return app()->get("db.{$alias}", $args);
    }

    /**
     * @inheritdoc
     *
     * @return DbFactoryQueryLoop
     */
    public function query_loop($query = [])
    {
        return $this->resolve('factory.query-loop', [$query, $this->db]);
    }

    /**
     * @inheritdoc
     *
     * @return DbFactorySelect
     */
    public function select($query = null)
    {
        return $this->resolve('factory.select', [$query, $this->db]);
    }
}