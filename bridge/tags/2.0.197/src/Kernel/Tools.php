<?php

namespace tiFy\Kernel;

use tiFy\Components\Tools\File\File;
use tiFy\Plugins\Bridge\Bridge;

/**
 * @method static File File()
 */
class Tools
{
    /**
     * Appel statique d'une librairie de la boîte à outils.
     *
     * @param string $name Nom de qualification de la librairie.
     * @param array $args Liste des variables passées en argument à la méthode.
     *
     * @return callable
     */
    public static function __callStatic($name, $args)
    {
        $alias = "tiFy\\Components\\Tools\\{$name}\\{$name}";
        if (!Bridge::instance()->has($alias)) :
            if (!class_exists($alias)) :
                wp_die(sprintf(__('La boîte à outils "%s" ne semble pas disponible', 'tify'), $name), __('Librairie indisponible', 'tify'), 500);
            endif;
            Bridge::instance()->add($alias);
        endif;

        return Bridge::instance()->get($alias, $args);
    }
}