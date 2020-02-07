<?php declare(strict_types=1);

namespace tiFy\Database;

use Illuminate\Database\Eloquent\Collection;

class MetaCollection extends Collection
{
    /**
     * Récupération d'un attribut.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->items) && count($this->items)) {
            $meta = $this->first(function ($meta) use ($key) {
                return $meta->meta_key === $key;
            });

            return $meta ? $meta->meta_value : null;
        }

        return null;
    }

    /**
     * Test d'existance d'un attribut.
     *
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name): bool
    {
        return !is_null($this->__get($name));
    }
}