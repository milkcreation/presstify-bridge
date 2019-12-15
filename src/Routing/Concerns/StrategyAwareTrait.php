<?php declare(strict_types=1);

namespace tiFy\Routing\Concerns;

use tiFy\Contracts\Routing\StrategyAwareTrait as StrategyAwareTraitContract;

/**
 * Trait StrategyAwareTrait
 * @package tiFy\Routing\Concerns
 *
 * @mixin ContainerAwareTrait
 */
trait StrategyAwareTrait
{
    /**
     * {@inheritdoc}
     *
     * @return StrategyAwareTrait
     */
    public function strategy($alias): StrategyAwareTraitContract
    {
        if (is_string($alias)) {
            if ($this->getContainer()->has("router.strategy.{$alias}")) {
                $strategy = $this->getContainer()->get("router.strategy.{$alias}");
            } elseif ($this->getContainer()->has($alias)) {
                $strategy = $this->getContainer()->get($alias);
            } elseif (class_exists($alias)) {
                $strategy = new $alias();
            }
        } else {
            $strategy = $alias;
        }

        $this->setStrategy($strategy ?? $this->strategy);

        return $this;
    }
}