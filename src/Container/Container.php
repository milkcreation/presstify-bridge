<?php declare(strict_types=1);

namespace tiFy\Container;

use League\Container\Argument\RawArgument;
use League\Container\Container as LeagueContainer;
use League\Container\ServiceProvider\ServiceProviderInterface;
use tiFy\Contracts\Container\Container as ContainerContract;

class Container extends LeagueContainer implements ContainerContract
{
    /**
     * Indicateur d'initialisation.
     * @var bool
     */
    protected $booted = false;

    /**
     * Liste des fournisseurs de service.
     * @var string[]
     */
    protected $serviceProviders = [];

    /**
     * @inheritDoc
     */
    public function boot(): ContainerContract
    {
        if (!$this->booted) {
            foreach ($this->getServiceProviders() as $serviceProvider) {
                $this->share($serviceProvider, $resolved = new $serviceProvider());

                if ($resolved instanceof ServiceProviderInterface) {
                    $resolved->setContainer($this);
                    $this->addServiceProvider($resolved);
                }
            }

            $this->booted = true;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function getFromThisContainer($alias, array $args = [])
    {
        array_walk($args, function (&$arg) {
            if (is_string($arg)) {
                $arg = new RawArgument($arg);
            }
        });

        return parent::getFromThisContainer($alias, $args);
    }

    /**
     * @inheritDoc
     */
    public function getServiceProviders()
    {
        return $this->serviceProviders;
    }

    /**
     * @inheritDoc
     */
    public function hasParameter()
    {
        return false;
    }
}