<?php

use tiFy\Tify12;
use tiFy\Contracts\Kernel\Request;
use tiFy\Contracts\Kernel\ParamsBag;
use tiFy\Routing\Router;

if (!function_exists('app')) :
    /**
     * Instance du controleur de l'application.
     *
     * @return Tify12
     */
    function app()
    {
        return Tify12::instance();
    }
endif;

if (!function_exists('params')) :
    /**
     * Instance de contrôleur de paramètres.
     *
     * @param mixed $params Liste des paramètres.
     *
     * @return ParamsBag
     */
    function params($params = [])
    {
        /** @var ParamsBag $factory */
        $manager = app()->get('params', $params);

        return $manager;
    }
endif;

if (!function_exists('request')) :
    /**
     * Instance de requête globale.
     *
     * @return Request
     */
    function request()
    {
        $manager = app()->get('request');

        return $manager;
    }
endif;

if (!function_exists('router')) :
    /**
     * Instance de requête globale.
     *
     * @return Router
     */
    function router()
    {
        $manager = app()->get('router');

        return $manager;
    }
endif;