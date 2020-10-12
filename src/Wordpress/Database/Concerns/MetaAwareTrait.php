<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Concerns;

use Exception;
use tiFy\Support\Str;

/**
 * @mixin \Corcel\Concerns\MetaFields
 * @property $meta_value
 */
trait MetaAwareTrait
{
    /**
     * @return mixed
     */
    public function getValueAttribute()
    {
        try {
            $value = is_string($this->meta_value) ? Str::unserialize($this->meta_value) : $this->meta_value;

            return $value === false && $this->meta_value !== false ? $this->meta_value : $value;
        } catch (Exception $ex) {
            return $this->meta_value;
        }
    }
}