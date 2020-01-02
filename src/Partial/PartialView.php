<?php declare(strict_types=1);

namespace tiFy\Partial;

use BadMethodCallException;
use Exception;
use tiFy\Contracts\Partial\PartialView as PartialViewContract;
use tiFy\View\Factory\PlatesFactory;

/**
 * @method string after()
 * @method string attrs()
 * @method string before()
 * @method string content()
 * @method string getAlias()
 * @method string getId()
 * @method string getIndex()
 */
class PartialView extends PlatesFactory implements PartialViewContract
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
    ];

    /**
     * @inheritDoc
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, $this->mixins)) {
            try {
                return call_user_func_array([$this->engine->params('partial'), $method], $parameters);
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