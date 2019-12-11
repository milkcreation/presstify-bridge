<?php

namespace tiFy\Form\Factory;

use Exception;
use tiFy\Contracts\Form\FormFactory;
use tiFy\Contracts\Form\FactoryValidation;
use tiFy\Validation\Validator as v;

class Validation implements FactoryValidation
{
    use ResolverTrait;

    /**
     * Cartographie des alias de fonction de contrôle d'intégrité
     * @var array
     */
    protected $alias = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param FormFactory $form Instance du contrôleur de formulaire.
     *
     * @return void
     */
    public function __construct(FormFactory $form)
    {
        $this->form = $form;
    }

    /**
     * @inheritDoc
     */
    public function call($callback, $value, $args = [])
    {
        $_args = $args;
        array_unshift($args, $value);

        if (is_string($callback)) {
            try {
                return !empty($_args) ? v::$callback(...$_args)->validate($value) : v::$callback()->validate($value);
            } catch (Exception $e) {
                if (is_callable([$this, $callback])) {
                    return call_user_func_array([$this, $callback], $args);
                } elseif (function_exists($callback)) {
                    return call_user_func_array($callback, $args);
                }
            }
        } elseif(is_callable($callback)) {
            return call_user_func_array($callback, $args);
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function __return_true($value)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function compare($value, $tags, $raw = true)
    {
        return v::equals($this->fieldTagValue($tags, $raw))->validate($value);
    }
}