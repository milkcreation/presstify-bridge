<?php declare(strict_types=1);

namespace tiFy\Contracts\Console;

use Symfony\Component\Console\{Application, Command\Command};
use tiFy\Console\CommandStack;

/**
 * @mixin Application
 */
interface Console
{
    /**
     * Création d'un jeu de commandes groupées.
     *
     * @param string $name
     * @param array $commands
     *
     * @return CommandStack|Command
     */
    public function stack(string $name, array $commands = []): Command;
}