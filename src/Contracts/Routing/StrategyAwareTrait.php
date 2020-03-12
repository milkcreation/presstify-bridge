<?php declare(strict_types=1);

namespace tiFy\Contracts\Routing;

use League\Route\Strategy\StrategyInterface;

interface StrategyAwareTrait
{
    /**
     * Définition d'une strategie de traitement de la route.
     *
     * @param string|StrategyInterface $alias
     *
     * @return static
     */
    public function strategy($alias): StrategyAwareTrait;
}