<?php

namespace tiFy\Contracts\Db;

interface DbFactoryResolverTrait
{
    /**
     * @inheritdoc
     *
     * @return DbFactory
     */
    public function db();

    /**
     * @inheritdoc
     *
     * @return DbFactoryHandle
     */
    public function handle();

    /**
     * @inheritdoc
     *
     * @return DbFactoryMake
     */
    public function make();

    /**
     * @inheritdoc
     *
     * @return DbFactoryMeta
     */
    public function meta();

    /**
     * @inheritdoc
     *
     * @return DbFactoryMetaQuery
     */
    public function meta_query($query);

    /**
     * @inheritdoc
     *
     * @return DbFactoryParser
     */
    public function parser();

    /**
     * @inheritdoc
     *
     * @return mixed
     */
    public function resolve($alias, $args = []);

    /**
     * @inheritdoc
     *
     * @return DbFactoryQueryLoop
     */
    public function query_loop($query_args = []);

    /**
     * @inheritdoc
     *
     * @return DbFactorySelect
     */
    public function select($query = null);
}