<?php declare(strict_types=1);

namespace tiFy\Wordpress\Option;

use Exception;
use BadMethodCallException;
use tiFy\View\Factory\PlatesFactory;

/**
 * @method bool isSettingsPage()
 */
class OptionPageView extends PlatesFactory
{
    /**
     * Liste des méthodes héritées.
     * @var array
     */
    protected $mixins = [
        'isSettingsPage'
    ];

    /**
     * @inheritDoc
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, $this->mixins)) {
            try {
                return call_user_func_array([$this->engine->params('option_page'), $method], $parameters);
            } catch (Exception $e) {
                throw new BadMethodCallException(
                    sprintf(
                        __('La méthode [%s] de la page d\'options n\'est pas disponible.', 'tify'),
                        $method
                    )
                );
            }
        } else {
            return parent::__call($method, $parameters);
        }
    }
}