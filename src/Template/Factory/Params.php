<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use tiFy\Contracts\Template\FactoryParams as FactoryParamsContract;
use tiFy\Contracts\Template\TemplateFactory;
use tiFy\Support\ParamsBag;

class Params extends ParamsBag implements FactoryParamsContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var TemplateFactory
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'singular' => $this->factory->label('singular') ?: $this->factory->name(),
            'plural'   => $this->factory->label('plural') ?: $this->factory->name(),
        ];
    }
}