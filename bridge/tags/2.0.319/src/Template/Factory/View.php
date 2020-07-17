<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use BadMethodCallException;
use Exception;
use tiFy\Contracts\Template\{
    FactoryForm,
    FactoryLabels,
    FactoryParams,
    FactoryRequest,
    FactoryUrl,
    FactoryViewer
};
use tiFy\View\Factory\PlatesFactory;

/**
 * @method FactoryForm form()
 * @method FactoryLabels|string label(?string $key = null, string $default = '')
 * @method string name()
 * @method FactoryParams|mixed param($key = null, $default = null)
 * @method FactoryRequest request()
 * @method FactoryUrl url()
 */
class View extends PlatesFactory implements FactoryViewer
{
    /**
     * Liste des méthodes heritées.
     * @var array
     */
    protected $mixins = [
        'form',
        'label',
        'name',
        'param',
        'request',
        'url',
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