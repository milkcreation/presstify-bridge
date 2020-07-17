<?php declare(strict_types=1);

namespace tiFy\Cookie;

use tiFy\Container\ServiceProvider;

class CookieServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = ['cookie'];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('cookie', function () {
            $cookie = (new Cookie())->setContainer($this->getContainer())->setArgs(
                config('cookie.value', null),
                config('cookie.expire', 3600),
                config('cookie.path', null),
                config('cookie.domain', null),
                config('cookie.secure', null),
                config('cookie.httpOnly', true),
                config('cookie.raw', false),
                config('cookie.sameSite', null)
            );

            if ($base64 = config('cookie.base64', false)) {
                $cookie->setBase64((bool)$base64);
            }

            if ($salt = config('cookie.salt', '')) {
                $cookie->setSalt((string)$salt);
            }

            return $cookie;
        });
    }
}
