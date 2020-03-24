<?php

use tiFy\Wordpress\Contracts\PageHook;
use tiFy\Wordpress\Contracts\PageHookItem;
use tiFy\Wordpress\Contracts\Wordpress;

if (!function_exists('page_hook')) :
    /**
     * Instance de controleur de page d'accroche
     * {@internal
     * - null $name Récupére l'instance du controleur.
     * - string $name Récupére l'instance du controleur de l'élément déclaré.
     * - array $name Déclaration des éléments
     * }
     *
     * @param null|string $name Nom de qualification de l'élément à récupérer.
     *
     * @return PageHook|PageHookItem
     */
    function page_hook($name = null)
    {
        /** @var PageHook $factory */
        $factory = app()->get('wp.page-hook');

        if (is_null($name)) :
            return $factory;
        elseif (is_array($name)) :
            return $factory->set($name);
        else :
            return $factory->get($name);
        endif;
    }
endif;

if (!function_exists('wordpress')) :
    /**
     * Instance du controleur d'environnement Wordpress.
     *
     * @return Wordpress
     */
    function wordpress(): Wordpress
    {
        return app()->get('wp');
    }
endif;