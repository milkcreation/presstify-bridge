<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use tiFy\Contracts\Template\FactoryUrl as FactoryUrlContract;
use tiFy\Contracts\Template\TemplateFactory;
use tiFy\Routing\Url as BaseUrl;

class Url extends BaseUrl implements FactoryUrlContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var TemplateFactory
     */
    protected $factory;

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(router(), request());
    }
}