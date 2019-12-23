<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use tiFy\Contracts\Template\FactoryLabels as FactoryLabelsContract;
use tiFy\Contracts\Template\TemplateFactory;
use tiFy\Support\LabelsBag;

class Labels extends LabelsBag implements FactoryLabelsContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var TemplateFactory
     */
    protected $factory;
}