<?php declare(strict_types=1);

namespace tiFy\Routing\Concerns;

use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Routing\ContainerAwareTrait as ContainerAwareTraitContract;

trait ContainerAwareTrait
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container
     */
    protected $container;

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * {@inheritDoc}
     *
     * @return ContainerAwareTrait
     */
    public function setContainer(Container $container): ContainerAwareTraitContract
    {
        $this->container = $container;

        return $this;
    }
}