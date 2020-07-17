<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use Exception;
use tiFy\Contracts\Template\FactoryActions as FactoryActionsContract;
use tiFy\Support\Str;

class Actions implements FactoryActionsContract
{
    use FactoryAwareTrait;

    /**
     * @inheritDoc
     */
    public function getIndex(): string
    {
        return 'action';
    }

    /**
     * @inheritDoc
     */
    public function execute(string $name, ...$parameters)
    {
        $method = 'execute' . Str::studly($name);
        if (method_exists($this, $method)) {
            return $this->$method(...$parameters);
        } else {
            throw new Exception(__('Impossible d\'exécuter l\'action.', 'tify'));
        }
    }
}