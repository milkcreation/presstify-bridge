<?php declare(strict_types=1);

namespace tiFy\Contracts\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Model
 * @mixin Builder
 */
interface ColumnsAwareTrait
{
    /**
     * Récupération de la liste des colonnes de la table.
     *
     * @return array
     */
    public function getColumns(): array;

    /**
     * Vérification d'une colonne dans la table.
     *
     * @param string $name Nom de qualification de la colonne.
     *
     * @return bool
     */
    public function hasColumn(string $name): bool;
}