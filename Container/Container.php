<?php

namespace tiFy\Container;

use League\Container\Container as LeagueContainer;
use tiFy\Contracts\Container\Container as ContainerContract;

class Container extends LeagueContainer implements ContainerContract
{
    /**
     * Liste des fournisseurs de services
     * @var string[]
     */
    protected $serviceProviders = [];

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        foreach($this->serviceProviders as $serviceProvider) :
            if (class_exists($serviceProvider)) :
                $this->addServiceProvider(new $serviceProvider($this));
            endif;
        endforeach;
    }
}