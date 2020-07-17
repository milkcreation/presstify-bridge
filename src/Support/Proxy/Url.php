<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use Psr\Http\Message\UriInterface;
use League\Uri\Contracts\UriInterface as LeagueUri;
use tiFy\Contracts\Routing\{Url as UrlContract, UrlFactory};

/**
 * @method static UrlContract appendSegment(string $segment)
 * @method static UrlFactory current(bool $full = true)
 * @method static UrlContract deleteSegment(string $segment)
 * @method static string decoded(bool $raw = true)
 * @method static LeagueUri|UriInterface get()
 * @method static array params(string|null $key = null, string|null $default = null);
 * @method static string|null rel(string $url)
 * @method static string rewriteBase()
 * @method static UrlFactory root(string $path = '')
 * @method static UrlContract set(UriInterface|LeagueUri|string $uri)
 * @method static string with(array $args)
 * @method static string without(string[] $args)
 */
class Url extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return UrlContract
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
        return 'url';
    }
}