<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use tiFy\Contracts\Template\TemplateFactory;
use tiFy\Contracts\Template\FactoryAwareTrait as FactoryAwareTraitContract;

/**
 * @mixin FactoryAwareTraitContract
 */
trait FactoryAwareTrait
{
    /**
     * Instance du gabarit d'affichage associé.
     * @var TemplateFactory
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function getFactory(): ?TemplateFactory
    {
        return $this->factory;
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function setTemplateFactory(TemplateFactory $factory): FactoryAwareTraitContract
    {
        $this->factory = $factory;

        return $this;
    }
}