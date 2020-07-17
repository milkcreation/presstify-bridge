<?php declare(strict_types=1);

namespace tiFy\Console;

use Exception;
use Symfony\Component\{
    Console\Exception\CommandNotFoundException,
    Console\Input\ArrayInput,
    Console\Input\InputInterface,
    Console\Input\InputOption,
    Console\Output\OutputInterface,
    Lock\Factory,
    Lock\LockInterface as Lock,
    Lock\Store\SemaphoreStore};
use tiFy\Contracts\Console\CommandStack as CommandStackContract;
use tiFy\Support\ParamsBag;

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
class CommandStack extends Command implements CommandStackContract
{
    /**
     * Liste des arguments d'exécution des commandes.
     * @var ParamsBag
     */
    protected $args;

    /**
     * Instance du verrou.
     * @var Lock
     */
    protected $lock;

    /**
     * Liste des arguments d'exécution par défaut de toutes les commandes.
     * @var array
     */
    protected $defaults = [];

    /**
     * Liste des noms de qualification des commandes associées.
     * @var array
     */
    protected $stack = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param string|null $name Nom de qualification de la commande.
     * @param string[]|array $commands Liste des commandes associées.
     *
     * @return void
     */
    public function __construct(string $name = null, array $commands = [])
    {
        parent::__construct($name);

        $this->setStack($commands);

        $this->args = new ParamsBag();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->addOption('url', null, InputOption::VALUE_OPTIONAL, __('Url du site', 'tify'), '')
            ->addOption('release', null, InputOption::VALUE_OPTIONAL, __('Libération du verrou', 'tify'), false)
            ->addOption('archive', null, InputOption::VALUE_OPTIONAL, __('Archivage des fichiers', 'tify'), true);
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->getLock()) {
            $factory = new Factory(new SemaphoreStore());
            $this->setLock($factory->createLock(md5($this->getName() . ($input->getOption('url') ?: ''))));
        }

        if ($input->getOption('release') !== false) {
            $this->getLock()->release();
            return 0;
        } elseif (!$this->getLock()->acquire()) {
            $output->writeln(__('Cette commande est déjà en cours d\'exécution.', 'tify'));
            return 0;
        } else {
            foreach ($this->getStack() as $name) {
                try {
                    try {
                        $command = $this->getApplication()->find($name);
                    } catch (CommandNotFoundException $e) {
                        $output->writeln(sprintf(__('La commande "%s" est introuvable.', 'tify'), $name));
                        continue;
                    }

                    $command->run(new ArrayInput(array_merge($this->defaults, $this->args->all())), $output);
                } catch (Exception $e) {
                    $output->writeln(sprintf(__('Impossible d\'exécuter la commande "%s".', 'tify'), $name));
                    continue;
                }
                $output->writeln('');
            }
        }

        $this->getLock()->release();

        return 0;
    }

    /**
     * @inheritDoc
     */
    public function addStack(string $name): CommandStackContract
    {
        if (!in_array($name, $this->stack)) {
            array_push($this->stack, $name);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLock(): ?Lock
    {
        return $this->lock;
    }

    /**
     * @inheritDoc
     */
    public function getStack(): array
    {
        return $this->stack;
    }

    /**
     * @inheritDoc
     */
    public function setCommandArgs($name, array $args): CommandStackContract
    {
        $this->args->set($name, $args);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDefaultArgs(array $args): CommandStackContract
    {
        $this->defaults = $args;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLock(Lock $lock): CommandStackContract
    {
        $this->lock = $lock;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setStack(array $stack): CommandStackContract
    {
        foreach ($stack as $name) {
            $this->addStack($name);
        }

        return $this;
    }
}