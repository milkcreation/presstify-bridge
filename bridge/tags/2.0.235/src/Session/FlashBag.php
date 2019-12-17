<?php declare(strict_types=1);

namespace tiFy\Session;

use tiFy\Contracts\Session\FlashBag as FlashBagContract;
use tiFy\Support\ParamsBag;

class FlashBag extends ParamsBag implements FlashBagContract
{
    /**
     * Nom de qualification de l'instance.
     * @var string
     */
    private $name = 'tiFyFlashes';

    /**
     * Non de qualification de la clé de stockage des données.
     * @var string
     */
    private $storageKey = '_tify_flashes';

    /**
     * @inheritDoc
     */
    public function add($key, $value): FlashBagContract
    {
        return $this->push($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        return $this->pull($key, $default);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getStorageKey()
    {
        return $this->storageKey;
    }

    /**
     * @inheritDoc
     */
    public function initialize(array &$flashes)
    {
        $this->attributes = &$flashes;
    }

    /**
     * @inheritDoc
     */
    public function peek($type, array $default = [])
    {
        return $this->has($type) ? $this->attributes[$type] : $default;
    }

    /**
     * @inheritDoc
     */
    public function peekAll()
    {
        return $this->all();
    }

    /**
     * @inheritDoc
     */
    public function setAll(array $messages)
    {
        $this->attributes = $messages;
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
