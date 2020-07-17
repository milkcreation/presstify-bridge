<?php declare(strict_types=1);

namespace tiFy\Form\Factory;

use BadMethodCallException;
use Exception;
use tiFy\Contracts\Form\{FormFactory, FactoryView as FactoryViewContract};
use tiFy\View\Factory\PlatesFactory;
use Closure;

/**
 * @method string csrf()
 * @method bool isSuccessed()
 * @method string tagName()
 */
class View extends PlatesFactory implements FactoryViewContract
{
    /**
     * Liste des méthodes héritées.
     * @var array
     */
    protected $mixins = [
        'csrf',
        'isSuccessed',
        'tagName'
    ];

    /**
     * @inheritDoc
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, $this->mixins)) {
            try {
                return call_user_func_array([$this->engine->params('form'), $method], $parameters);
            } catch (Exception $e) {
                throw new BadMethodCallException(
                    sprintf(
                        __('La méthode [%s] du formulaire n\'est pas disponible.', 'tify'),
                        $method
                    )
                );
            }
        } else {
            return parent::__call($method, $parameters);
        }
    }

    /**
     * @inheritDoc
     */
    public function after(): string
    {
        if ($content = $this->form()->get('after')) {
            if ($content instanceof Closure) {
                return call_user_func($content);
            } elseif (is_string($content)) {
                return $content;
            }
        }

        return '';
    }

    /**
     * @inheritDoc
     */
    public function before(): string
    {
        if ($content = $this->form()->get('before')) {
            if ($content instanceof Closure) {
                return call_user_func($content);
            } elseif (is_string($content)) {
                return $content;
            }
        }

        return '';
    }

    /**
     * @inheritDoc
     */
    public function form(): FormFactory
    {
        return $this->engine->params('form');
    }
}