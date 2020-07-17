<?php declare(strict_types=1);

namespace tiFy\Database\Concerns;

use tiFy\Contracts\Database\ConnectionAwareTrait as ConnectionAwareTraitContract;

/**
 * @mixin ConnectionAwareTraitContract
 */
trait ConnectionAwareTrait
{
    /**
     * @inheritDoc
     */
    public function getTablePrefix(): string
    {
        return $this->getConnection()->getTablePrefix();
    }
}