<?php declare(strict_types=1);

namespace tiFy\Metabox;

use BadMethodCallException;
use Exception;
use tiFy\Contracts\Metabox\MetaboxView as MetaboxViewContract;
use tiFy\View\ViewController;

/**
 * @method string name()
 * @method mixed params(string|array|null $key = null, mixed $default = null)
 * @method mixed value(string|null $key = null, mixed $default = null)
 */
class MetaboxView extends ViewController implements MetaboxViewContract
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
     * Délégation d'appel des méthodes de l'application associée.
     *
     * @param string $name Nom de la méthode à appeler.
     * @param array $arguments Liste des variables passées en argument.
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        try {
            $metabox = $this->engine->params('metabox');
            if (!in_array($name, $this->mixins)) {
                throw new BadMethodCallException(sprintf(__(
                    'La méthode [%s] de boîte de saisie ne peut être appelée dans un gabarit d\'affichage.', 'tify'
                ), $name));
            }
            return $metabox->{$name}(...$arguments);
        } catch (Exception $e) {
            throw new BadMethodCallException(
                sprintf(__('La méthode [%s] de metabox n\'est pas disponible.', 'tify'), $name)
            );
        }
    }
}