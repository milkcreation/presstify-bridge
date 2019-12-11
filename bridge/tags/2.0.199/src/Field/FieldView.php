<?php declare(strict_types=1);

namespace tiFy\Field;

use Exception;
use BadMethodCallException;
use tiFy\Contracts\Field\FieldView as FieldViewContract;
use tiFy\View\ViewController;

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
class FieldView extends ViewController implements FieldViewContract
{
    /**
     * Liste des méthodes héritées.
     * @var array
     */
    protected $mixins = [
        'after',
        'attrs',
        'before',
        'content',
        'getAlias',
        'getId',
        'getIndex',
        'getName',
        'getValue'
    ];

    /**
     * @inheritDoc
     */
    public function __call($name, $arguments)
    {
        try {
            if (in_array($name, $this->mixins)) {
                return call_user_func_array([$this->engine->params('field'), $name], $arguments);
            } else {
                throw new BadMethodCallException(
                    sprintf(
                        __('La méthode %s du controleur de champs n\'est pas permise.', 'tify'),
                        $name
                    )
                );
            }
        } catch (Exception $e) {
            throw new BadMethodCallException(
                sprintf(
                    __('La méthode %s du controleur de champs n\'est pas disponible.', 'tify'),
                    $name
                )
            );
        }
    }
}