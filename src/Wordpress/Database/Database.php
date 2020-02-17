<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database;

use tiFy\Contracts\Database\Database as DatabaseManager;
use tiFy\Wordpress\Contracts\Database\Database as DatabaseContract;

class Database implements DatabaseContract
{
    /**
     * Instance du controleur de gestion des bases de données.
     * @var DatabaseManager
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param DatabaseManager $manager Instance du controleur des bases de données.
     *
     * @return void
     */
    public function __construct(DatabaseManager $manager)
    {
        $this->manager = $manager;

        global $wpdb;

        $this->manager->addConnection([
            'driver'    => 'mysql',
            'host'      => getenv('DB_HOST'),
            'database'  => getenv('DB_DATABASE'),
            'username'  => getenv('DB_USERNAME'),
            'password'  => getenv('DB_PASSWORD'),
            'charset'   => $wpdb->charset,
            'collation' => $wpdb->collate,
            'prefix'    => $wpdb->base_prefix
        ], 'wp_user');

        //$this->manager->getConnection()->setTablePrefix($wpdb->prefix);
    }
}