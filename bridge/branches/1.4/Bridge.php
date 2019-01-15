<?php

namespace tiFy;

use tiFy\Container\Container;
use tiFy\Contracts\Tify;
use tiFy\Kernel\KernelServiceProvider;
use tiFy\Routing\RoutingServiceProvider;
use tiFy\Wp\WpServiceProvider;

class Bridge extends Container implements tiFy
{
    /**
     * Instance statique de l'instance de l'application.
     * @var static
     */
    private static $instance;

    /**
     * Liste des fournisseurs de services
     * @var string[]
     */
    protected $serviceProviders = [
        KernelServiceProvider::class,
        RoutingServiceProvider::class,
        WpServiceProvider::class
    ];

    /**
     * CONSTRUCTEUR.
     *
     * Liste des
     *
     * @return void
     */
    public function __construct($providers = [])
    {
        self::$instance = $this;

        foreach($providers as $provider) :
            array_push($this->serviceProviders, $provider);
        endforeach;

        parent::__construct();

        $this->share('app', $this);
    }

    /**
     * @inheritdoc
     */
    public static function instance()
    {
        return self::$instance;
    }
}
