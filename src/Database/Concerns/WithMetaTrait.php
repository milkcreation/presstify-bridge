<?php declare(strict_types=1);

namespace tiFy\Database\Concerns;

use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Support\Collection;
use tiFy\Contracts\Database\WithMetaTrait as WithMetaTraitContract;
use tiFy\Database\{MetaCollection, MetaModel};
use tiFy\Support\{Arr, Str};

/**
 * @mixin WithMetaTraitContract
 */
trait WithMetaTrait
{
    /**
     * Liste des metadonnées
     * @var MetaCollection|MetaModel[]
     */
    protected $meta;

    /**
     * @inheritDoc
     */
    public function getMeta($attribute)
    {
        if ($meta = $this->meta->{$attribute}) {
            return is_string($meta) ? Str::unserialize($meta) : $meta;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getMetaKeys(): array
    {
        return $this->meta->pluck('meta_key', 'meta_id')->all();
    }

    /**
     * @inheritDoc
     */
    public function getMetaValues(): array
    {
        return $this->meta->pluck('meta_value', 'meta_id')->all();
    }

    /**
     * @inheritDoc
     */
    public function meta()
    {
        return $this->hasMany($this->metaModel, "{$this->table}_id");
    }

    /**
     * @inheritDoc
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        if ($metaCollection = $this->getAttribute('meta')) {
            foreach ($metaCollection as $meta) {
                $attributes[$meta->meta_key] = $attributes[$meta->meta_key] ?? $meta->meta_value;
            }
        }

        return $attributes;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return Model|Collection
     */
    public function createMeta($key, $value = null)
    {
        if (is_array($key)) {
            return collect($key)->map(function ($value, $key) {
                return $this->createOneMeta($key, $value);
            });
        }

        return $this->createOneMeta($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return Model
     */
    private function createOneMeta($key, $value)
    {
        $meta = $this->meta()->create([
            'meta_key'   => $key,
            'meta_value' => Arr::serialize($value),
        ]);
        $this->load('meta');

        return $meta;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return bool
     */
    public function saveMeta($key, $value = null)
    {
        $value = Arr::serialize($value);

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->saveOneMeta($k, $v);
            }
            $this->load('meta');

            return true;
        }

        return $this->saveOneMeta($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return bool
     */
    private function saveOneMeta($key, $value)
    {
        $meta = $this->meta()->where('meta_key', $key)
                     ->firstOrNew(['meta_key' => $key]);

        $result = $meta->fill(['meta_value' => $value])->save();
        $this->load('meta');

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function scopeHasMeta(Builder $query, $meta, $value = null, string $operator = '=')
    {
        if ( ! is_array($meta)) {
            $meta = [$meta => $value];
        }

        foreach ($meta as $key => $value) {
            $query->whereHas('meta', function (Builder $query) use ($key, $value, $operator) {
                if ( ! is_string($key)) {
                    return $query->where('meta_key', $operator, $value);
                }
                $query->where('meta_key', $operator, $key);

                return is_null($value) ? $query :
                    $query->where('meta_value', $operator, $value);
            });
        }

        return $query;
    }

    /**
     * @inheritDoc
     */
    public function __get($key)
    {
        $value = parent::__get($key);

        if ($value === null && ! property_exists($this, $key)) {
            return $this->meta->$key;
        }

        return $value;
    }
}