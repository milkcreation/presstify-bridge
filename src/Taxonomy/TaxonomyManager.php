<?php declare(strict_types=1);

namespace tiFy\Taxonomy;

use tiFy\Contracts\Taxonomy\TaxonomyFactory as TaxonomyFactoryContract;
use tiFy\Contracts\Taxonomy\TaxonomyManager as TaxonomyManagerContract;
use tiFy\Contracts\Taxonomy\TaxonomyTermMeta;
use tiFy\Support\Manager;

class TaxonomyManager extends Manager implements TaxonomyManagerContract
{
    /**
     * @inheritdoc
     */
    public function get(...$args): ?TaxonomyFactoryContract
    {
        return parent::get($args[0]);
    }

    /**
     * @inheritdoc
     */
    public function register($name, ...$args): TaxonomyManagerContract
    {
        return $this->set([$name => $args[0] ?? []]);
    }

    /**
     * @inheritdoc
     */
    public function term_meta(): ?TaxonomyTermMeta
    {
        return $this->resolve('term-meta');
    }

    /**
     * @inheritdoc
     */
    public function resolve(string $alias)
    {
        return $this->container->get("taxonomy.{$alias}");
    }

    /**
     * @inheritdoc
     */
    public function walk(&$item, $key = null): void
    {
        if (!$item instanceof TaxonomyFactoryContract) {
            $item = new TaxonomyFactory($key, $item);
        }
        $item->setManager($this)->boot();
    }
}