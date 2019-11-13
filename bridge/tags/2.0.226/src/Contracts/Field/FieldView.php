<?php declare(strict_types=1);

namespace tiFy\Contracts\Field;

use BadMethodCallException;
use tiFy\Contracts\View\ViewController;

/**
 * @method string after()
 * @method string attrs()
 * @method string before()
 * @method string content()
 * @method string getAlias()
 * @method string getId()
 * @method string getIndex()
 * @method string getName()
 * @method string getValue()
 */
interface FieldView extends ViewController
{
    /**
     * Délégation d'appel des méthodes permises du controleur de champ.
     *
     * @param string $name Nom de la méthode à appeler.
     * @param array $arguments Liste des variables passées en argument.
     *
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call($name, $arguments);
}