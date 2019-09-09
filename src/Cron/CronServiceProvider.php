<?php declare(strict_types=1);

namespace tiFy\Cron;

use tiFy\Container\ServiceProvider;

class CronServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = [
        'cron',
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->getContainer()->share('cron', function () {
            return (new CronManager($this->getContainer()->get('app')))->set(config('cron', []));
        });
    }
}