<?php declare(strict_types=1);

namespace tiFy\Console;

use tiFy\Contracts\Console\Console as ConsoleContract;
use Symfony\Component\Console\{Application as BaseApplication, Command\Command};
class Console extends BaseApplication implements ConsoleContract
{
    /**
     * @inheritDoc
     */
    public function stack(string $name, array $commands = []): Command
    {
        return $this->add(new CommandStack($name, $commands));
    }
}