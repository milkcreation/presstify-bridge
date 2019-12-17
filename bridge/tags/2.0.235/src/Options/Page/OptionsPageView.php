<?php

namespace tiFy\Options\Page;

use tiFy\View\ViewController;

/**
 * Class OptionsPageView
 */
class OptionsPageView extends ViewController
{
    /**
     * Liste des méthodes héritées.
     * @var array
     */
    protected $mixins = [];

    /**
     * Translation d'appel des méthodes de l'application associée.
     *
     * @param string $name Nom de la méthode à appeler.
     * @param array $arguments Liste des variables passées en argument.
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, $this->mixins)) :
            return call_user_func_array(
                [$this->engine->params('options_page'), $name],
                $arguments
            );
        endif;
    }
}