<?php

namespace tiFy\Contracts;

interface Tify
{
    /**
     * Récupération statique de l'instance de l'application.
     *
     * @return static
     */
    public static function instance();
}