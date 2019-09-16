<?php

namespace tiFy\Db;

use tiFy\Container\ServiceProvider;
use tiFy\Db\Factory\Handle;
use tiFy\Db\Factory\Make;
use tiFy\Db\Factory\Meta;
use tiFy\Db\Factory\MetaQuery;
use tiFy\Db\Factory\Parser;
use tiFy\Db\Factory\QueryLoop;
use tiFy\Db\Factory\Select;

class DbServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = [
        'db',
        'db.factory',
        'db.factory.handle',
        'db.factory.make',
        'db.factory.meta',
        'db.factory.meta-query',
        'db.factory.parser',
        'db.factory.query-loop',
        'db.factory.select'
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->registerManager();

        $this->registerFactory();
    }

    /**
     * Déclaration du gestionnaire de bases de données.
     *
     * @return void
     */
    public function registerManager()
    {
        $this->getContainer()->share('db', DbManager::class);
    }

    /**
     * Déclaration des constructeur de base de données.
     *
     * @return void
     */
    public function registerFactory()
    {
        $this->getContainer()->add('db.factory', DbFactory::class);

        $this->getContainer()->add('db.factory.handle', Handle::class);

        $this->getContainer()->add('db.factory.make', Make::class);

        $this->getContainer()->add('db.factory.meta', Meta::class);

        $this->getContainer()->add('db.factory.meta-query', MetaQuery::class);

        $this->getContainer()->add('db.factory.parser', Parser::class);

        $this->getContainer()->add('db.factory.query-loop', QueryLoop::class);

        $this->getContainer()->add('db.factory.select', Select::class);
    }
}