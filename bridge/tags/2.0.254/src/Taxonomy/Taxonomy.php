<?php declare(strict_types=1);

namespace tiFy\Taxonomy;

use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Taxonomy\{
    Taxonomy as TaxonomyContract,
    TaxonomyFactory as TaxonomyFactoryContract,
    TaxonomyTermMeta as TaxonomyTermMetaContract
};

class Taxonomy implements TaxonomyContract
{
    /**
     * Instance du conteneur d'injection de dépendance.
     * @var Container|null
     */
    protected $container;

    /**
     * Liste des instances de type de post déclarée.
     * @var TaxonomyFactoryContract[]|array
     */
    protected $items = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param Container|null $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function get(string $name): ?TaxonomyFactoryContract
    {
        return $this->items[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function meta(): TaxonomyTermMetaContract
    {
        return ($c = $this->getContainer()) ? $c->get('taxonomy.term-meta') : new TaxonomyTermMeta();
    }

    /**
     * @inheritDoc
     */
    public function register(string $name, array $args = []): TaxonomyFactoryContract
    {
        return $this->items[$name] = (new TaxonomyFactory($name, $args))->setManager($this)->prepare();
    }

    /**
     * @deprecated
     */
    public function term_meta(): ?TaxonomyTermMetaContract
    {
        return $this->getContainer()->get('term-meta');
    }
}