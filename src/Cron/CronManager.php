<?php declare(strict_types=1);

namespace tiFy\Cron;

use Psr\Container\ContainerInterface as Container;
use Illuminate\Support\Collection;
use tiFy\Contracts\Cron\{
    CronManager as CronManagerContract,
    CronJob as CronJobContract
};
use tiFy\Support\Manager;

/**
 * USAGE
 * ---------------------------------------------------------------------------------------------------------------------
 * Configurer une tâche planifiée
 * 1. Dans le fichier wp-config.php, désactiver l'appel navigateur des tâches cron (recommandé).
 * > define('DISABLE_WP_CRON', true);
 *
 * 2. Sur le serveur (MacOS ou Linux), configurer l'exécution cli des tâches planifiées.
 * > $ crontab -e
 * > $ * * * * * curl -I http(s)://%site_url%/wp-cron.php?doing_wp_cron > /dev/null 2>&1
 *
 * Tester une tâche planifiée depuis le navigateur ou en console. Le mode test de la tâche doit être actif.
 * IMPORTANT : N'utiliser cette fonctionnalité qu'en développement uniquement.
 * Désactiver absolument le mode test en production.
 * > http(s)://%site_url%/?job=%task%
 */
class CronManager extends Manager implements CronManagerContract
{
    /**
     * CONSTRUCTEUR.
     *
     * @param Container|null $container
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        parent::__construct($container);

        add_action('init', function () {
            foreach(get_option('cron_job_infos', []) as $hook => $attrs) {
                $exists = $this->collect()->first(function (CronJob $item) use ($hook) {
                    return $item['hook'] === $hook;
                });

                if (!$exists) {
                    $this->clear($hook);
                }
            }

            $jobs = $this->collect()->mapWithKeys(function ($item) {
                return [$item['hook'] => []];
            })->all();

            update_option('cron_job_infos', array_merge($jobs, get_option('cron_job_infos', [])), false);

            if ($jobs = $this->all()) {
                template()->register('cron.layout.list', [
                    'admin_menu' => [
                        'menu_slug'   => 'CronLayoutList',
                        'parent_slug' => 'tools.php',
                        'page_title'  => __('Gestion des tâches planifiées', 'tify'),
                        'menu_title'  => __('Tâches planifiées', 'tify')
                    ],
                    'content'    => function () {
                        $jobs = $this->all();

                        return view()
                            ->setDirectory(__DIR__ . '/views')
                            ->make('job-list', compact('jobs'));
                    }
                ]);
            }

            if (($job = request()->get('job', '')) && ($item = $this->getJob($job))) {
                do_action($item->getHook());
                exit;
            } elseif (!defined('DOING_CRON') ||  (DOING_CRON!==true)) {
                foreach ($this->items as $job) {
                    $this->_schedule($job);
                }
            }
        }, 999999);
    }

    /**
     * Programmation des tâches.
     *
     * @param CronJob $job
     *
     * @return void
     */
    private function _schedule(CronJob $job)
    {
        if (($freq = wp_get_schedule($job->getHook())) && ($freq !== $job->getFrequency())) {
            $this->clear($job->getHook());
        }

        if (!wp_next_scheduled($job->getHook())) {
            wp_schedule_event(
                $job->getTimestamp(),
                $job->getFrequency(),
                $job->getHook()
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function clear($hook)
    {
        wp_clear_scheduled_hook($hook);

        if (($jobs = get_option('cron_job_infos', [])) && isset($jobs[$hook])) {
            unset($jobs[$hook]);
            update_option('cron_job_infos', $jobs, false);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function collect(): Collection
    {
        return new Collection($this->items);
    }

    /**
     * @inheritDoc
     */
    public function getJob($name): CronJobContract
    {
        return $this->items[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function register($name, ...$args): ?CronJobContract
    {
        return $this->set($name, $attrs = $args[0] ?? [])->getJob($name);
    }

    /**
     * @inheritDoc
     */
    public function walk(&$job, $name = null): void
    {
        if (!$job instanceof CronJob) {
            $job = (new CronJob((string)$name))->setParams(is_array($job) ? $job : []);
        }

        $this->items[$name] = $job;
    }
}