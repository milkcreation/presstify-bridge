<?php declare(strict_types=1);

namespace tiFy\Cron;

use Exception;
use tiFy\Contracts\{
    Cron\CronJob as CronJobContract,
    Log\Logger as LoggerContract
};
use tiFy\Log\Logger;
use tiFy\Support\{
    DateTime,
    ParamsBag
};

class CronJob extends ParamsBag implements CronJobContract
{
    /**
     * Instance du gestionnaire de journalisation.
     * @var LoggerContract|null
     */
    protected $logger;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification.
     *
     * @return void
     */
    public function __construct(string $name)
    {
        $this->name = $name;

        add_action($this->getHook(), $this);
    }

    /**
     * Execution d'une instance de la classe.
     *
     * @return void
     */
    final public function __invoke(): void
    {
        if (wp_doing_cron() || $this->onTest()) {
            $start = $this->getDatetime()->setTimestamp(time());

            set_time_limit(0);

            is_callable($this->getCommand())
                ? call_user_func_array($this->getCommand(), [$this->getArgs(), $this])
                : $this->exec();

            $end = $this->getDatetime()->setTimestamp(time());

            $this->updateInfo('last', $end->getTimestamp());

            $this->logger()->notice(
                sprintf(
                    __('La tâche "%s" démarrée le %s s\'est terminée le %s'),
                    $this->getName(),
                    $start->format('d/m/Y à H:i:s'),
                    $end->format('d/m/Y à H:i:s')
                )
            );
            exit;
        } elseif(!$this->onTest()) {
            wp_die(
                sprintf(
                    __(
                        '<h3>La mode TEST doit être actif</h3>' .
                        '<p>Pour afficher le résultat depuis un navigateur, ' .
                        'activer le mode test pour la tâche <em>%s</em>.</p>' .
                        '<b>Attention, veillez à desactiver le mode test de vos tâches en production.</b>',
                        'tify'
                    ),
                    $this->getName()
                ),
                __('Mode test inactif', 'tify'),
                500
            );
        }
    }

    /**
     * @inheritDoc
     *
     * @return array {
     *      @var string $title Intitulé de qualification.
     *      @var string $description Description.
     *      @var int|string|DateTime $date Date de déclenchement de la première itération.
     *      @var string $freq Fréquence d'exécution des itérations.
     *      @var callable $command
     *      @var array $args Liste des variables complémentaires passées en arguments.
     *      @var boolean|array $log Liste des attributs de configuration de la journalisation.
     * }
     */
    public function defaults()
    {
        return [
            'hook'          => 'cron.' . $this->getName(),
            'title'         => $this->getName(),
            'description'   => '',
            'date'          => date('Y-m-d H:i:s', mktime(0, 0, 0, 01, 01, 1971)),
            'freq'          => 'daily',
            'command'       => [$this, 'exec'],
            'args'          => [],
            'logger'        => true,
            'test'          => false
        ];
    }

    /**
     * @inheritDoc
     */
    public function exec(): void {}

    /**
     * @inheritDoc
     */
    public function getArgs(): array
    {
        return $this->get('args', []);
    }

    /**
     * @inheritDoc
     */
    public function getCommand(): ?callable
    {
        return is_callable($command = $this->get('command')) ? $command : null;
    }

    /**
     * @inheritDoc
     */
    public function getDate(): DateTime
    {
        return $this->get('date');
    }

    /**
     * @inheritDoc
     */
    public function getDatetime($time = 'now'): ?DateTime
    {
        try{
            return new DateTime($time);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return $this->get('description');
    }

    /**
     * @inheritDoc
     */
    public function getFrequency(): string
    {
        return $this->get('freq');
    }

    /**
     * @inheritDoc
     */
    public function getHook(): string
    {
        return $this->get('hook');
    }

    /**
     * @inheritDoc
     */
    public function getInfo($key, $default = null)
    {
        $infos = get_option('cron_job_infos', []);

        return (isset($infos[$this->getHook()][$key]))
            ? $infos[$this->getHook()][$key]
            : $default;
    }

    /**
     * @inheritDoc
     */
    public function getLastDate(): ?DateTime
    {
        return ($timestamp = (int)$this->getInfo('last'))
            ? $this->getDatetime()->setTimestamp($timestamp)
            : null;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getNextDate(): ?DateTime
    {
        return ($timestamp = wp_next_scheduled($this->getHook()))
            ? $this->getDatetime()->setTimestamp($timestamp)
            : null;
    }

    /**
     * @inheritDoc
     */
    public function getTimestamp(): int
    {
        return $this->getDate()->getTimestamp();
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return $this->get('title');
    }

    /**
     * @inheritDoc
     */
    public function logger(): LoggerContract
    {
        return $this->get('logger');
    }

    /**
     * @inheritDoc
     */
    public function onTest(): bool
    {
        return (bool)$this->get('test', false);
    }

    /**
     * @inheritdoc
     */
    public function parse(): CronJobContract
    {
        parent::parse();

        $date = $this->get('date');
        if (!$date instanceof DateTime) {
            $this->set('date', $this->getDatetime($date));
        }

        if ($logger = $this->pull('logger')) {
            if (!$logger instanceof LoggerContract) {
                $logger = (new Logger('cron'))->setParams(is_array($logger) ? $logger : []);
            }
            $this->setLogger($logger);
        }

        $freq = $this->get('freq');
        $recurrences = wp_get_schedules();
        if (is_array($freq)) {
            if (!$freq_id = $this->get('freq.id')) {
                $freq_id = 'daily';
            } else {
                add_filter('cron_schedules', function () use ($freq) {
                    $attrs = array_merge([
                        'interval' => DAY_IN_SECONDS,
                        'display'  => __('Once Daily'),
                    ], $freq);

                    return [
                        $attrs['id'] => [
                            'interval' => $attrs['interval'],
                            'display'  => $attrs['display'],
                        ],
                    ];
                });
            }
        } else {
            if (is_string($freq)) {
                $freq_id = isset($recurrences[$freq]) ? $freq : 'daily';
            } else {
                $freq_id = 'daily';
            }
        }
        $this->set('freq', $freq_id);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLogger(LoggerContract $logger): CronJobContract
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setParams(array $params): CronJobContract
    {
        return $this->set($params)->parse();
    }

    /**
     * @inheritDoc
     */
    public function updateInfo($key, $value): CronJobContract
    {
        $jobs = get_option('cron_job_infos', []);
        $jobs[$this->getHook()][$key] = $value;
        update_option('cron_job_infos', $jobs, false);

        return $this;
    }
}