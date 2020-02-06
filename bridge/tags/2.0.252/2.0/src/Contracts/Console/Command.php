<?php declare(strict_types=1);

namespace tiFy\Contracts\Console;

use Symfony\Component\Console\Command\Command as BaseCommand;

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

/**
 * @mixin BaseCommand
 */
interface Command
{

}