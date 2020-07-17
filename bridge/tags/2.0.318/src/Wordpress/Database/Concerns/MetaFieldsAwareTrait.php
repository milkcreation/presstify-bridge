<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Concerns;

use tiFy\Support\{Arr, Str};

/**
 * @mixin \Corcel\Concerns\MetaFields
 * @mixin \Illuminate\Database\Eloquent\Model
 * @property $meta_value
 */
trait MetaFieldsAwareTrait
{
    /**
     * @param string $attribute
     *
     * @return mixed|null
     */
    public function getMeta($attribute)
    {
        if ($meta = $this->meta->{$attribute}) {
            return is_string($meta) ? Str::unserialize($meta) : $meta;
        }

        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return bool
     */
    public function saveMeta($key, $value = null)
    {
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
        $meta = $this->meta()->where('meta_key', $key)->firstOrNew(['meta_key' => $key]);

        $value = Arr::serialize($value);

        $result = $meta->fill(['meta_value' => $value])->save();
        $this->load('meta');

        return $result;
    }
}