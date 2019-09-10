<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Form\FormFactory;
use tiFy\Contracts\Form\FormManager;

/**
 * @method static FormManager all()
 * @method static FormFactory|null get(string $name)
 * @method static FormManager set(string|array $key, FormFactory|array|null $value = null)
 */
class Form extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'form';
    }
}