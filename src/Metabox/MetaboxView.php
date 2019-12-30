<?php declare(strict_types=1);

namespace tiFy\Metabox;

use BadMethodCallException;
use Exception;
use tiFy\Contracts\Metabox\MetaboxView as MetaboxViewContract;
use tiFy\View\Factory\PlatesFactory;

/**
 * @method string name()
 * @method mixed params(string|array|null $key = null, mixed $default = null)
 * @method mixed value(string|null $key = null, mixed $default = null)
 */
class MetaboxView extends PlatesFactory implements MetaboxViewContract
{
    /**
     * Liste des méthodes de délégation permises.
     * @var array
     */
    protected $mixins = [
        'name',
        'params',
        'value',
    ];

    /**
     * @inheritDoc
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, $this->mixins)) {
            try {
                return call_user_func_array([$this->engine->params('metabox'), $method], $parameters);
            } catch (Exception $e) {
                throw new BadMethodCallException(
                    sprintf(
                        __('La méthode [%s] de la boîte de saisie n\'est pas disponible.', 'tify'),
                        $method
                    )
                );
            }
        } else {
            return parent::__call($method, $parameters);
        }
    }
}