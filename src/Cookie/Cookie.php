<?php declare(strict_types=1);

namespace tiFy\Cookie;

use Psr\Container\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Cookie as SfCookie;
use tiFy\Contracts\Cookie\Cookie as CookieContract;
use tiFy\Validation\Validator as v;
use tiFy\Support\Arr;
use tiFy\Support\Proxy\{Request, Url};

class Cookie implements CookieContract
{
    /**
     * Instances des cookies déclarées.
     * @var CookieContract[]
     */
    public static $cookies = [];

    /**
     * Activation de l'encodage de la valeur du cookie en base64.
     * @var boolean
     */
    protected $base64 = false;

    /**
     * Conteneur d'injection de dépendances.
     * @var Container|null
     */
    protected $container;

    /**
     * Nom de qualification du domaine.
     * @var string|null
     */
    protected $domain;

    /**
     * Délai d'expiration du cookie.
     * @var int
     */
    protected $expire = 0;

    /**
     * Limitation de l'accessibilité du cookie au protocole HTTP.
     * @var boolean
     */
    protected $httpOnly = true;

    /**
     * Nom de qualification du cookie.
     * @var string
     */
    protected $name;

    /**
     * Chemin relatif de validation.
     * @var string|null
     */
    protected $path;

    /**
     * Indicateur de mise en file du cookie en vue de son traitement dans la requête globale.
     * @var bool
     */
    protected $queued = false;

    /**
     * Indicateur d'activation de l'encodage d'url lors de l'envoi du cookie.
     * @var boolean
     */
    protected $raw = false;

    /**
     * Suffixe de salage du nom de qualification du cookie.
     * @var string
     */
    protected $salt = '';

    /**
     * Directive de permission d'envoi du cookie.
     * @see https://developer.mozilla.org/fr/docs/Web/HTTP/Headers/Set-Cookie
     * @var string|null Strict|Lax
     */
    protected $sameSite = false;

    /**
     * Indicateur d'activation du protocole sécurisé HTTPS.
     * @var boolean
     */
    protected $secure = false;

    /**
     * Valeur d'enregistrement du cookie.
     * @var mixed
     */
    protected $value = null;

    /**
     * @inheritDoc
     */
    public function clear(): CookieContract
    {
        $this->setArgs(null, -(60 * 60 * 24 * 365 * 5))->setQueued();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function create(?array ...$args): SfCookie
    {
        $value = $args[0] ?? $this->value;

        if (!is_null($value)) {
            if (is_array($value)) {
                $value = json_encode($value);
            }

            if ($this->base64) {
                $value = base64_encode($value);
            }
        }

        $expire = $args[1] ?? $this->expire;

        $args = [
            $this->getName(),
            $value,
            $expire === 0 ? 0 : time() + $expire,
            $args[2] ?? $this->path,
            $args[3] ?? $this->domain,
            $args[4] ?? $this->secure,
            $args[5] ?? $this->httpOnly,
            $args[6] ?? $this->raw,
            $args[7] ?? $this->sameSite,
        ];

        return new SfCookie(...$args);
    }

    /**
     * @inheritDoc
     */
    public static function fetchQueued(): array
    {
        $queued = [];

        foreach(self::$cookies as $cookie) {
            if ($cookie->isQueued()) {
                $queued[] = $cookie->create();
                $cookie->setQueued(false);
            }
        }

        return $queued;
    }

    /**
     * @inheritDoc
     */
    public function get(?string $key = null, $default = null)
    {
        if (!$value = Request::cookie($this->getName())) {
            return $default;
        }

        if(!$this->raw) {
            $value = rawurldecode($value);
        }

        if ($this->base64 && v::base64()->validate($value)) {
            $value = base64_decode($value);
        }

        if (v::json()->validate($value)) {
            $value = json_decode($value, true);
        }

        return is_null($key) ? $value : Arr::get($value, $key, $default);
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name . $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function isQueued(): bool
    {
        return $this->queued;
    }

    /**
     * @inheritDoc
     */
    public function make(string $alias, $attrs = null): CookieContract
    {
        if (!isset(self::$cookies[$alias])) {
            $cookie = ($provided = $this->getContainer()->get('cookie')) ? clone $provided : new static();

            if (is_null($attrs)) {
                $cookie->setName($alias);
            } elseif (is_string($attrs)) {
                $cookie->setName($attrs);
            } elseif (is_array($attrs)) {
                $cookie->setName(isset($attrs['name']) ? (string)$attrs['name'] : $alias);

                if (isset($attrs['base64'])) {
                    $cookie->setBase64(filter_var($attrs['base64'], FILTER_VALIDATE_BOOLEAN));
                }

                if (isset($attrs['salt'])) {
                    $cookie->setSalt((string)$attrs['salt']);
                }

                $cookie->setArgs(
                    $attrs['value'] ?? null,
                    isset($attrs['expire']) ? (int)$attrs['expire'] : 0,
                    isset($attrs['path']) ? (string)$attrs['path'] : $cookie->getPath(),
                    isset($attrs['domain']) ? (string)$attrs['domain'] : $cookie->getDomain(),
                    isset($attrs['secure']) ? filter_var($attrs['secure'] ?? true, FILTER_VALIDATE_BOOLEAN) : null,
                    filter_var($attrs['httpOnly'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    filter_var($attrs['raw'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    isset($attrs['sameSite']) ? (string)$attrs['sameSite'] : null
                );
            }

            self::$cookies[$alias] = $cookie;
        }

        return self::$cookies[$alias];
    }

    /**
     * @inheritDoc
     */
    public function set($value = null, ?array ...$args): CookieContract
    {
        return $this->setArgs($value, ...$args)->setQueued();
    }

    /**
     * @inheritDoc
     */
    public function setArgs(
        $value = null,
        int $expire = 0,
        ?string $path = null,
        ?string $domain = null,
        ?bool $secure = null,
        bool $httpOnly = true,
        bool $raw = false,
        string $sameSite = null
    ): CookieContract {
        [
            $this->value,
            $this->expire,
            $this->path,
            $this->domain,
            $this->secure,
            $this->httpOnly,
            $this->raw,
            $this->sameSite,
        ] = [$value, $expire, $path, $domain, $secure, $httpOnly, $raw, $sameSite];

        if (is_null($this->path)) {
            $this->path = rtrim(ltrim(Url::rewriteBase(), '/'), '/');
            $this->path = $this->path ? "/{$this->path}/" : '/';
        }

        if (is_null($this->domain)) {
            $this->domain = Request::getHost();
        }

        if (is_null($this->secure)) {
            $this->secure = Request::isSecure();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setBase64(bool $active = false): CookieContract
    {
        $this->base64 = $active;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setContainer(Container $container): CookieContract
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDomain(?string $domain = null): CookieContract
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): CookieContract
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPath(?string $path = null): CookieContract
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setQueued(bool $queued = true): CookieContract
    {
        $this->queued = $queued;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSalt(string $salt = ''): CookieContract
    {
        $this->salt = $salt;

        return $this;
    }
}