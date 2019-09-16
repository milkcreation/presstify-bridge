<?php declare(strict_types=1);

namespace tiFy\Contracts\Http;

use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use tiFy\Contracts\Support\ParamsBag;

interface SessionFlashBag extends ParamsBag, SessionBagInterface
{
    /**
     * Ajout d'un attribut.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return static
     */
    public function add($key, $value): SessionFlashBag;
}
