<?php declare(strict_types=1);

namespace tiFy\Contracts\Cron;

use Illuminate\Support\Collection;
use tiFy\Contracts\Support\Manager;

interface CronManager extends Manager
{
    /**
     * Suppression d'une tâche planifiée selon son identifiant d'action.
     *
     * @param string $hook Identifiant de qualification de l'action.
     *
     * @return static
     */
    public function clear($hook);

    /**
     * Récupération d'une instance de la liste des tâches déclarées.
     *
     * @return Collection
     */
    public function collect(): Collection;

    /**
     * Récupération d'une tâche planifiée déclarée.
     *
     * @param string $name Nom de qualification de l'élément.
     *
     * @return CronJob|null
     */
    public function getJob($name): ?CronJob;

    /**
     * @inheritDoc
     */
    public function register($name, ...$args): ?CronJob;
}