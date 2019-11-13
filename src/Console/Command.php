<?php declare(strict_types=1);

namespace tiFy\Console;

use Exception;
use Symfony\Component\Console\{
    Command\Command as BaseCommand,
    Input\InputInterface,
    Output\OutputInterface
};
use tiFy\Contracts\Console\Command as CommandContract;

/**
 * USAGE :
 * Liste des commandes disponibles
 * -------------------------------
 * php console list
 *
 * TIPS :
 * Arrêt complet des commandes CLI lancées
 * ---------------------------------------
 * pkill -9 php
 */
class Command extends BaseCommand implements CommandContract
{
    /**
     * @inheritDoc
     */
    protected function configure() { }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output) { }
}