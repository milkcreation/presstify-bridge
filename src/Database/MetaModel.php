<?php declare(strict_types=1);

namespace tiFy\Database;

use Exception;

/**
 * @property mixed $meta_value
 */
abstract class MetaModel extends Model
{
    /**
     * Clé primaire.
     * @var string
     */
    protected $primaryKey = 'meta_id';

    /**
     * Désactivation de l'horadatage.
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['meta_key', 'meta_value'];

    /**
     * @return mixed
     */
    public function getValueAttribute()
    {
        try {
            $value = unserialize($this->meta_value);

            return $value === false && $this->meta_value !== false ? $this->meta_value : $value;
        } catch (Exception $e) {
            return $this->meta_value;
        }
    }

    /**
     * Initialisation d'une nouvelle collection.
     *
     * @param array $models
     *
     * @return MetaCollection
     */
    public function newCollection(array $models = [])
    {
        return new MetaCollection($models);
    }
}