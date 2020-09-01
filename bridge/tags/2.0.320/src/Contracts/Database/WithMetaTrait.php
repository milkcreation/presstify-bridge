<?php declare(strict_types=1);

namespace tiFy\Contracts\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin Model
 * @mixin Builder
 */
interface WithMetaTrait
{
    /**
     * Récupération d'une métadonnées.
     *
     * @param string $key Indice de qualification de la métadonnée.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMeta(string $key, $default = null);

    /**
     * Récupération de la liste des clés d'indice de qualification des métadonnées.
     *
     * @return array
     */
    public function getMetaKeys(): array;

    /**
     * Récupération de la liste des valeurs des métadonnées.
     *
     * @return array
     */
    public function getMetaValues(): array;

    /**
     * Définition de la relation entre la table principale et la table des métadonnées.
     *
     * @return HasMany
     */
    public function meta(): HasMany;

    /**
     * @inheritDoc
     */
    public function scopeHasMeta(Builder $query, $meta, $value = null, string $operator = '=');

    /**
     * Récupération d'un attribut.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key);
}