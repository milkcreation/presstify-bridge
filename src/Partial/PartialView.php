<?php declare(strict_types=1);

namespace tiFy\Partial;

use BadMethodCallException;
use Exception;
use tiFy\Contracts\Partial\PartialView as PartialViewContract;
use tiFy\View\ViewController;

/**
 * @method string after()
 * @method string attrs()
 * @method string before()
 * @method string content()
 * @method string getAlias()
 * @method string getId()
 * @method string getIndex()
 */
class PartialView extends ViewController implements PartialViewContract
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
        'getIndex'
    ];

    /**
     * @inheritDoc
     */
    public function __call($name, $arguments)
    {
        try {
            if (in_array($name, $this->mixins)) {
                return call_user_func_array([$this->engine->params('partial'), $name], $arguments);
            } else {
                throw new BadMethodCallException(
                    sprintf(
                        __('La méthode %s du controleur de portion d\'affichage n\'est pas permise.', 'tify'),
                        $name
                    )
                );
            }
        } catch (Exception $e) {
            throw new BadMethodCallException(
                sprintf(
                    __('La méthode %s du controleur de portion d\'affichage n\'est pas disponible.', 'tify'),
                    $name
                )
            );
        }
    }
}