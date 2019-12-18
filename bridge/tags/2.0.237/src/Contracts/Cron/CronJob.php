<?php declare(strict_types=1);

namespace tiFy\Contracts\Cron;

use tiFy\Contracts\{
    Log\Logger,
    Support\ParamsBag
};
use tiFy\Support\DateTime;

interface CronJob extends ParamsBag
{
    /**
     * Lancement de la commande à executer.
     *
     * @return void
     */
    public function exec(): void;

    /**
     * Récupération des variables passées en arguments.
     *
     * @return array
     */
    public function getArgs(): array;

    /**
     * Récupération de la commande à executer.
     *
     * @return false|callable
     */
    public function getCommand(): ?callable;

    /**
     * Récupération de la date d'exécution de la première itération.
     *
     * @return DateTime
     */
    public function getDate(): DateTime;

    /**
     * Récupération d'une instance du gestionnaire de date.
     * {@internal La timezone correspond aux réglages de l'application.}
     *
     * @param string $time Date
     *
     * @return DateTime|null
     */
    public function getDatetime($time = 'now'): ?DateTime;

    /**
     * Récupération de la description.
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Récupération de la fréquence d'exécution des itérations.
     *
     * @return string
     */
    public function getFrequency(): string;

    /**
     * Récupération de l'accroche de l'action de déclenchement
     *
     * @return string
     */
    public function getHook(): string;

    /**
     * Récupération d'une information stockée en base.
     *
     * @param string $key Indice de qualification.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getInfo($key, $default = null);

    /**
     * Récupération de la date de la dernière exécution de la tâche.
     *
     * @return DateTime
     */
    public function getLastDate(): ?DateTime;

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération de la date de la prochaine exécution de la tâche.
     *
     * @return DateTime
     */
    public function getNextDate(): ?DateTime;

    /**
     * Récupération de l'horodatage d'exécution de la première itération.
     *
     * @return int
     */
    public function getTimestamp(): int;

    /**
     * Récupération de l'intitulé de qualification.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Récupération de l'instance du controleur de journalisation.
     *
     * @return Logger
     */
    public function logger(): Logger;

    /**
     * Vérification de l'activité du mode test.
     *
     * @return boolean
     */
    public function onTest(): bool ;

    /**
     * Définition de l'instance du gestionnaire de journalisation.
     *
     * @param Logger $logger
     *
     * @return static
     */
    public function setLogger(Logger $logger): CronJob;

    /**
     * Définition de la liste des paramètres de configuration.
     *
     * @param array $params
     *
     * @return static
     */
    public function setParams(array $params): CronJob;

    /**
     * Mise à jour d'une information stockée en base.
     *
     * @param string $key Indice de qualification.
     * @param mixed $value Valeur de l'info.
     *
     * @return static
     */
    public function updateInfo($key, $value): CronJob;
}