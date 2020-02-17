<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use BadMethodCallException;
use Exception;
use tiFy\Contracts\Template\{FactoryLabels, FactoryParams, FactoryRequest, FactoryViewer as FactoryViewerContract};
use tiFy\View\Factory\PlatesFactory;

/**
 * @method FactoryLabels|string label(?string $key = null, string $default = '')
 * @method string name()
 * @method FactoryParams|mixed param($key = null, $default = null)
 * @method FactoryRequest request()
 */
class View extends PlatesFactory implements FactoryViewerContract
{
    /**
     * Liste des méthodes heritées.
     * @var array
     */
    protected $mixins = [
        'label',
        'name',
        'param',
        'request'
    ];

    /**
     * @inheritDoc
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, $this->mixins)) {
            try {
                return call_user_func_array([$this->engine->params('template'), $method], $parameters);
            } catch (Exception $e) {
                throw new BadMethodCallException(
                    sprintf(
                        __('La méthode [%s] du template n\'est pas disponible.', 'tify'),
                        $method
                    )
                );
            }
        } else {
            return parent::__call($method, $parameters);
        }
    }
}