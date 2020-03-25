<?php declare(strict_types=1);

namespace tiFy\Validation\Rules;

use Psr\Container\ContainerInterface as Container;
use Respect\Validation\Rules\AbstractRule as BaseAbstractRule;
use tiFy\Contracts\Validation\Rule;
use tiFy\Contracts\Validation\Validator;

abstract class AbstractRule extends BaseAbstractRule implements Rule
{
    /**
     * Instance du gestionnaire de validation.
     * @var Validator
     */
    protected $validator;

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->validator ? $this->validator->getContainer() : null;
    }

    /**
     * @inheritDoc
     */
    public function setArgs(...$args): Rule
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setValidator(Validator $validator): Rule
    {
        $this->validator = $validator;

        return $this;
    }
}