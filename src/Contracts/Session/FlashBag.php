<?php declare(strict_types=1);

namespace tiFy\Contracts\Session;

use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use tiFy\Contracts\Support\ParamsBag;

interface FlashBag extends ParamsBag, SessionBagInterface
{
    /**
     * Ajout d'un attribut.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return static
     */
    public function add($key, $value): FlashBag;
}
