<?php declare(strict_types=1);

namespace tiFy\Wordpress\Proxy;

use tiFy\Support\Proxy\AbstractProxy;
use tiFy\Wordpress\Contracts\Option\{Option as OptionContract, OptionPage};

/**
 * @method static OptionPage|null getPage(string $name)
 * @method static OptionPage|null registerPage(string $name, array|OptionContract $attrs = [])
 */
class Option extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return OptionContract
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @inheritDoc
     */
    public static function getInstanceIdentifier()
    {
        return 'wp.option';
    }
}