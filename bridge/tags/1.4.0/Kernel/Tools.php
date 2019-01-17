<?php

namespace tiFy\Kernel;

use tiFy\Kernel\Tools\File;
use tiFy\Kernel\Tools\Functions;
use tiFy\Kernel\Tools\Html;
use tiFy\Kernel\Tools\Str;

/**
 * Class Tools
 *
 * @method static File File()
 * @method static Functions Functions()
 * @method static Html Html()
 * @method static Str Str()
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
        return app()->get('tools.' . strtolower($name), $args);
    }
}