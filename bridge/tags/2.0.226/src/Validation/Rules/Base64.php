<?php declare(strict_types=1);

namespace tiFy\Validation\Rules;

class Base64 extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function validate($input): bool
    {
        return $this->validator::regex('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/')->validate($input);
    }
}