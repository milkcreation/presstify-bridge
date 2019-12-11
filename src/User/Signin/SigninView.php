<?php

namespace tiFy\User\Signin;

use BadMethodCallException;
use Exception;
use tiFy\View\ViewController;

/**
 * Class SigninView
 *
 * @method string getMessages(string $type)
 */
class SigninView extends ViewController
{
    /**
     * Liste des méthodes héritées.
     * @var array
     */
    protected $mixins = [
        'getMessages'
    ];

    /**
     * Délégation d'appel des méthodes du formulaire d'authentification associé.
     *
     * @param string $name Nom de la méthode à appeler.
     * @param array $arguments Liste des variables passées en argument.
     *
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call($name, $arguments)
    {
        try {
            return $this->engine()->params('signin')->$name(...$arguments);
        } catch (Exception $e) {
            throw new BadMethodCallException(sprintf(__('La méthode %s n\'est pas disponible.', 'tify'), $name));
        }
    }
}