<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use tiFy\Contracts\Http\Session as SessionContract;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface as FlashBag;

/**
 * @method static FlashBag|SessionContract|mixed flash(string|array|null $key = null, mixed $value = null)
 */
class Session extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'session';
    }
}