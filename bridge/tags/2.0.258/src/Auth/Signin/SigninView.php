<?php declare(strict_types=1);

namespace tiFy\Auth\Signin;

use BadMethodCallException;
use Exception;
use tiFy\View\Factory\PlatesFactory;

/**
 * @method array getMessages(string $type)
 * @method string getName()
 */
class SigninView extends PlatesFactory
{
    /**
     * Liste des méthodes héritées.
     * @var array
     */
    protected $mixins = [
        'getMessages',
        'getName'
    ];

    /**
     * @inheritDoc
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, $this->mixins)) {
            try {
                return call_user_func_array([$this->engine->params('signin'), $method], $parameters);
            } catch (Exception $e) {
                throw new BadMethodCallException(
                    sprintf(
                        __('La méthode [%s] de la portion d\'affichage n\'est pas disponible.', 'tify'),
                        $method
                    )
                );
            }
        } else {
            return parent::__call($method, $parameters);
        }
    }
}