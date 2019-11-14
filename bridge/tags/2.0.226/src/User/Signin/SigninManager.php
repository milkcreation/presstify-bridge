<?php declare(strict_types=1);

namespace tiFy\User\Signin;

use tiFy\Contracts\User\SigninFactory as SigninFactoryContract;
use tiFy\Contracts\User\SigninManager as SigninManagerContract;
use tiFy\Support\Manager;

class SigninManager extends Manager implements SigninManagerContract
{
    /**
     * @inheritDoc
     */
    public function register($name, ...$args): SigninManagerContract
    {
        return $this->set([$name => $args[0] ?? []]);
    }

    /**
     * @inheritDoc
     */
    public function walk(&$item, $key = null): void
    {
        if (!$item instanceof SigninFactoryContract) {
            $attrs = $item;
            $item = $this->getContainer()
                ? $this->getContainer()->get(SigninFactoryContract::class)
                : new SigninFactory();
            $item->set($attrs);
        }

        $this->items[$key] = $item->prepare((string)$key, $this);
    }
}