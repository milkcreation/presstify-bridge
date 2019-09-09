<?php declare(strict_types=1);

namespace tiFy\Console;

use Exception;
use Symfony\Component\Console\{
    Command\Command as BaseCommand,
    Input\InputInterface,
    Output\OutputInterface
};

/**
 * USAGE :
 * Liste des commandes disponibles
 * -------------------------------
 * php console list
 *
 * Arrêt complet des commandes CLI lancées
 * ---------------------------------------
 * pkill -9 php
 */
class Command extends BaseCommand
{
    /**
     * @inheritdoc
     */
    protected function configure() { }

    /**
     * @inheritdoc
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output) { }
}