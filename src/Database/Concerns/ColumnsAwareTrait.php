<?php declare(strict_types=1);

namespace tiFy\Database\Concerns;

use tiFy\Contracts\Database\ColumnsAwareTrait as ColumnsAwareTraitContract;

/**
 * @mixin ColumnsAwareTraitContract
 */
trait ColumnsAwareTrait
{
    /**
     * Liste des colonnes de la table.
     * @var array
     */
    protected $columns;

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        if (is_null($this->columns)) {
            $this->columns = $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable())
                ?: [];
        }

        return $this->columns;
    }

    /**
     * @inheritDoc
     */
    public function hasColumn(string $name): bool
    {
        return in_array($name, $this->getColumns());
    }
}