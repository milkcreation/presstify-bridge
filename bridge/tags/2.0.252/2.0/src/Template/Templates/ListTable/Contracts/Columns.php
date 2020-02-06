<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable\Contracts;

use tiFy\Contracts\Support\Collection;
use tiFy\Contracts\Template\FactoryAwareTrait;

interface Columns extends Collection, FactoryAwareTrait
{
    /**
     * Récupération de la liste des colonnes.
     *
     * @return Column[]
     */
    public function all();

    /**
     * Récupération du nombre de colonne affichée.
     *
     * @return int
     */
    public function countVisible(): int;

    /**
     * {@inheritDoc}
     *
     * @return Column|null
     */
    public function get($key): ?Column;

    /**
     * Récupération de la liste des colonnes pouvant être masquées.
     *
     * @return Collection
     */
    public function getHideable(): iterable;

    /**
     * Récupération de la liste des noms de qualification des colonnes masquées.
     *
     * @return string[]
     */
    public function getHidden(): array;

    /**
     * Récupération du nom de qualification de la colonne principale.
     *
     * @return string
     */
    public function getPrimary(): string;

    /**
     * Récupération de la liste des noms de qualification des colonnes ouverte à l'ordonnacement.
     *
     * @return string[]
     */
    public function getSortable(): array;

    /**
     * Récupération de la liste des noms de qualification des colonnes visibles.
     *
     * @return string[]
     */
    public function getVisible(): array;

    /**
     * Traitement de la liste des colonnes.
     *
     * @param array $columns Liste des colonnes.
     *
     * @return static
     */
    public function parse(array $columns = []): Columns;

    /**
     * Affichage de l'interface de masquage des colonnes dans la table.
     *
     * @return string
     */
    public function renderToggle(): string;
}