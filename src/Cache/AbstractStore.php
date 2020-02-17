<?php declare(strict_types=1);

namespace tiFy\Cache;

use tiFy\Contracts\Cache\Store;
use tiFy\Support\DateTime;

abstract class AbstractStore implements Store
{
    /**
     * Préfixe de qualification des éléments en cache.
     * @var string
     */
    protected $prefix = '';

    /**
     * @inheritDoc
     */
    public function currentTime(): int
    {
        return DateTime::now()->getTimestamp();
    }

    /**
     * @inheritDoc
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @inheritDoc
     */
    public function has($key): bool
    {
        return !is_null($this->get($key));
    }

    /**
     * @inheritDoc
     */
    public function many(array $keys): ?array
    {
        $return = [];
        foreach ($keys as $key) {
            $return[$key] = $this->get($key);
        }
        return $return;
    }

    /**
     * @inheritDoc
     */
    public function putMany(array $values, $seconds): bool
    {
        $manyResult = null;

        foreach ($values as $key => $value) {
            $result = $this->put($key, $value, $seconds);
            $manyResult = is_null($manyResult) ? $result : ($result && $manyResult);
        }

        return $manyResult ?: false;
    }

    /**
     * @inheritDoc
     */
    public function serialize($value): string
    {
        return serialize($value);
    }

    /**
     * @inheritDoc
     */
    public function setPrefix(string $prefix): Store
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function unserialize(string $value)
    {
        return unserialize($value);
    }
}