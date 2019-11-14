<?php declare(strict_types=1);

namespace tiFy\Contracts\Log;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use tiFy\Contracts\Support\Manager;

interface LogManager extends LoggerInterface, Manager
{
    /**
     * Récupération d'un controleur de journalisation déclaré.
     *
     * @param string $name Nom de qualification d'un controleur déclaré.
     *
     * @return Logger|null
     */
    public function channel(string $name = null): ?Logger;

    /**
     * Déclaration et récupération d'un controleur de journalisation.
     *
     * @param string $name Nom de qualification d'un controleur.
     * @param array $params Liste des paramètres de configuration.
     *
     * @return Logger|null
     *
     * @throws InvalidArgumentException
     */
    public function registerChannel(string $name, array $params = []): ?Logger;
}