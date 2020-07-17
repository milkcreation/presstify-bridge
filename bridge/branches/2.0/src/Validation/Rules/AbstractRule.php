<?php declare(strict_types=1);

namespace tiFy\Validation\Rules;

use Respect\Validation\Rules\AbstractRule as BaseAbstractRule;
use tiFy\Contracts\Validation\Rule;

abstract class AbstractRule extends BaseAbstractRule implements Rule
{
    /**
     * @inheritDoc
     */
    public function setArgs(...$args): Rule
    {
        return $this;
    }
}