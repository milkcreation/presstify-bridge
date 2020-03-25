<?php declare(strict_types=1);

namespace tiFy\Cookie;

use Psr\Container\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Cookie as SfCookie;
use tiFy\Contracts\{Cookie\Cookie as CookieContract, Http\Response};
use tiFy\Validation\Validator as v;
use tiFy\Support\{Arr, Str};
use tiFy\Support\Proxy\{Request as req, Response as resp};

class Cookie implements CookieContract
{
    /**
     * Instances déclarées.
     * @var CookieContract[]
     */
    public static $instances = [];

    /**
     * Activation de l'encodage de la valeur du cookie en base64.
     * @var boolean
     */
    protected $base64 = false;

    /**
     * Instance du conteneur d'injection de dépendance.
     * @return Container
     */
    protected $container;

    /**
     * Nom de qualification du domaine du site associé.
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
     * Chemin relatif de validaté des cookies.
     * @var string|null
     */
    protected $path;

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
     * CONSTRUCTEUR.
     *
     * @param Container|null $container Conteneur d'injection de dépendance.
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function clear(): Response
    {
        $cookie = $this->generate(null, -60 * 60 * 24 * 365 * 5);

        $response = resp::instance();
        $response->headers->setCookie($cookie);

        return $response->send();
    }

    /**
     * @inheritDoc
     */
    public function generate($value = null, ...$args): SfCookie
    {
        $args = $this->parseArgs($value, ...$args);

        return new SfCookie(...$args);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key = null, $default = null)
    {
        if (!$value = req::cookie($this->getName())) {
            return $default;
        }

        if ($this->base64 && v::base64()->validate($value)) {
            $value = base64_decode($value);
        }

        $value = Str::unserialize($value);

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
    public function instance(string $alias, $attrs = null): CookieContract
    {
        if (!isset(self::$instances[$alias])) {
            self::$instances[$alias] = $this->container && $this->container->has('cookie')
                ? clone $this->container->get('cookie')
                : new static();

            if (is_null($attrs)) {
                self::$instances[$alias]->setName($alias);
            } elseif (is_string($attrs)) {
                self::$instances[$alias]->setName($attrs);
            } elseif (is_array($attrs)) {
                self::$instances[$alias]->setName(isset($attrs['name']) ? (string)$attrs['name'] : $alias);

                if (isset($attrs['base64'])) {
                    self::$instances[$alias]->setBase64((bool)$attrs['base64']);
                }

                if (isset($attrs['salt'])) {
                    self::$instances[$alias]->setSalt((string)$attrs['salt']);
                }

                self::$instances[$alias]->setDefaults(
                    $attrs['value'] ?? null,
                    isset($attrs['expire']) ? (int)$attrs['expire'] : 0,
                    isset($attrs['path']) ? (string)$attrs['path'] : self::$instances[$alias]->getPath(),
                    isset($attrs['$domain']) ? (string)$attrs['$domain'] : self::$instances[$alias]->getDomain(),
                    isset($attrs['secure']) ? (bool)$attrs['secure'] : null,
                    $attrs['httpOnly'] ?? true,
                    $attrs['raw'] ?? false,
                    isset($attrs['sameSite']) ? (string)$attrs['sameSite'] : null
                );
            }
        }

        return self::$instances[$alias];
    }

    /**
     * @inheritDoc
     */
    public function parseArgs(...$args): array
    {
        $value = $args[0] ?? $this->value;

        if (!is_null($value)) {
            $value = Arr::serialize($value);
            if ($this->base64) {
                $value = base64_encode($value);
            }
        }

        $expire = $args[1] ?? $this->expire;

        return [
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
    }

    /**
     * @inheritDoc
     */
    public function set($value = null, ...$args): Response
    {
        $cookie = $this->generate($value, ...$args);

        $response = resp::instance();
        $response->headers->setCookie($cookie);

        return $response->sendHeaders();
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
    public function setSalt(string $salt = ''): CookieContract
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDefaults(
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
            $this->path = rtrim(ltrim(url()->rewriteBase(), '/'), '/');
            $this->path = $this->path ? "/{$this->path}/" : '/';
        }

        if (is_null($this->domain)) {
            $this->domain = req::getHost();
        }

        if (is_null($this->secure)) {
            $this->secure = req::isSecure();
        }

        return $this;
    }
}

