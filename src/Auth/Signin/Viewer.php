<?php declare(strict_types=1);

namespace tiFy\Auth\Signin;

use BadMethodCallException;
use Exception;
use tiFy\View\ViewController as BaseViewer;

/**
 * @method array getMessages(string $type)
 * @method string getName()
 */
class Viewer extends BaseViewer
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