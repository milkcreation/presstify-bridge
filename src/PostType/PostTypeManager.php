<?php declare(strict_types=1);

namespace tiFy\PostType;

use tiFy\Contracts\PostType\{PostTypeFactory as PostTypeFactoryContract,
    PostTypeManager as PostTypeManagerContract,
    PostTypePostMeta,
    PostTypeStatus as PostTypeStatusContract};
use tiFy\Support\Manager;

class PostTypeManager extends Manager implements PostTypeManagerContract
{
    /**
     * Liste des statut déclarés.
     * @var PostTypeStatusContract[]
     */
    protected $statuses = [];

    /**
     * @inheritDoc
     */
    public function get(...$args): ?PostTypeFactoryContract
    {
        return parent::get($args[0]);
    }

    /**
     * @inheritDoc
     */
    public function post_meta(): PostTypePostMeta
    {
        return $this->resolve('post-meta');
    }

    /**
     * @inheritDoc
     */
    public function register($name, ...$args): PostTypeManagerContract
    {
        return $this->set([$name => $args[0] ?? []]);
    }

    /**
     * @inheritDoc
     */
    public function resolve(string $alias)
    {
        return $this->container->get("post-type.{$alias}");
    }

    /**
     * @inheritDoc
     */
    public function status(string $name): PostTypeStatusContract
    {
        return $this->statuses[$name] = $this->statuses[$name] ?? PostTypeStatus::createFromName($name);
    }

    /**
     * @inheritDoc
     */
    public function walk(&$item, $key = null): void
    {
        if (!$item instanceof PostTypeFactoryContract) {
            $item = new PostTypeFactory($key, $item);
        }
        $item->setManager($this)->boot();
    }
}