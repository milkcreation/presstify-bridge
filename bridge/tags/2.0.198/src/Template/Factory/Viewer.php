<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use BadMethodCallException;
use Exception;
use tiFy\Contracts\Template\{FactoryLabels, FactoryParams, FactoryRequest, FactoryViewer as FactoryViewerContract};
use tiFy\Contracts\Template\TemplateFactory;
use tiFy\View\ViewController;

/**
 * @method FactoryLabels|string label(?string $key = null, string $default = '')
 * @method string name()
 * @method FactoryParams|mixed param($key = null, $default = null)
 * @method FactoryRequest request()
 */
class Viewer extends ViewController implements FactoryViewerContract
{
    /**
     * Instance du gabarit d'affichage.
     * @var TemplateFactory
     */
    protected $factory;

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
     * Appel des méthodes héritées du motif d'affichage associée.
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
        if (in_array($name, $this->mixins)) {
            try {
                return $this->getEngine()->get('factory')->$name(...$arguments);
            } catch (Exception $e) {
                throw new BadMethodCallException(sprintf(__('La méthode %s n\'est pas disponible.', 'tify'), $name));
            }
        } else {
            return null;
        }
    }
}