<?php

namespace tiFy\Api;

use Psr\Container\ContainerInterface;

class Api
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var ContainerInterface
     */
    protected $container;

    /**
     * CONSTRUCTEUR
     *
     * @param ContainerInterface $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        foreach(config('api', []) as $service => $attrs) {
            if ($this->container->has("api.{$service}")) {
                $this->container->get("api.{$service}");
            }
        }
    }
}