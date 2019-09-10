<?php declare(strict_types=1);

namespace tiFy\Routing\Concerns;

use Psr\Container\ContainerInterface;
use tiFy\Contracts\Routing\ContainerAwareTrait as ContainerAwareTraitContract;

trait ContainerAwareTrait
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @inheritdoc
     */
    public function getContainer(): ?ContainerInterface
    {
        return $this->container;
    }

    /**
     * {@inheritdoc}
     *
     * @return ContainerAwareTrait
     */
    public function setContainer(ContainerInterface $container): ContainerAwareTraitContract
    {
        $this->container = $container;

        return $this;
    }
}