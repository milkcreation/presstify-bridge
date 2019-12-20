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
    protected $provides = [
        'cookie'
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('cookie', function () {
            $instance = (new Cookie($this->getContainer()))->setDefaults(
                config('cookie.value', null),
                (int)config('cookie.expire', 3600),
                ($path = config('cookie.path', null)) ? (string) $path : null,
                ($domain = config('cookie.domain', null)) ? (string)$domain : null,
                ($secure = config('cookie.secure', null)) ? (bool)$secure : null,
                ($httpOnly = config('cookie.httpOnly')) ? (bool)$httpOnly : true,
                ($raw = config('cookie.raw', false)) ? (bool)$raw : false,
                ($sameSite = config('cookie.sameSite', null)) ? (string)$sameSite : null
            );

            if ($base64 = config('cookie.base64', false)) {
                $instance->setBase64((bool)$base64);
            }

            if ($salt = config('cookie.salt', '')) {
                $instance->setSalt((string)$salt);
            }

            return $instance;
        });
    }
}
