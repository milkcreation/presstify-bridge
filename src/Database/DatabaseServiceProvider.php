<?php declare(strict_types=1);

namespace tiFy\Database;

use tiFy\Container\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'database',
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('database', function () {
            $manager = new Database();
            $manager->addConnection([
                'driver'    => 'mysql',
                'host'      => getenv('DB_HOST'),
                'database'  => getenv('DB_DATABASE'),
                'username'  => getenv('DB_USERNAME'),
                'password'  => getenv('DB_PASSWORD'),
                'charset'   => getenv('DB_CHARSET') ?: 'utf8mb4',
                'collation' => getenv('DB_COLLATE') ?: 'utf8mb4_unicode_ci',
                'prefix'    => getenv('DB_PREFIX') ?: '',
            ]);

            $manager->setAsGlobal();

            $manager->bootEloquent();

            return $manager;
        });
    }
}