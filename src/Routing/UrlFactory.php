<?php declare(strict_types=1);

namespace tiFy\Routing;

use Psr\Http\Message\UriInterface;
use League\Uri\{
    Contracts\UriInterface as LeagueUri,
    Http,
    Components\Query,
    UriModifier,
};
use tiFy\Contracts\Routing\UrlFactory as UrlFactoryContract;

class UrlFactory implements UrlFactoryContract
{
    /**
     * Instance de l'url.
     * @var LeagueUri|UriInterface|null
     */
    protected $uri;

    /**
     * CONSTRUCTEUR
     *
     * @param UriInterface|LeagueUri|string $uri
     *
     * @return void
     */
    public function __construct($uri)
    {
        $this->set($uri);
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @inheritDoc
     */
    public function appendSegment(string $segment): UrlFactoryContract
    {
        $this->uri = UriModifier::appendSegment($this->uri, $segment);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function deleteSegment(string $segment): UrlFactoryContract
    {
        if (preg_match("#{$segment}#", $this->uri->getPath(), $matches)) {
            $this->uri = $this->uri->withPath(preg_replace("#{$matches[0]}#", '', $this->uri->getPath()));
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function decoded(bool $raw = true): string
    {
        return $raw ? rawurldecode((string)$this->uri) : urldecode((string)$this->uri);
    }

    /**
     * @inheritDoc
     */
    public function params(?string $key = null, ?string $default = null)
    {
        parse_str($this->uri->getQuery(), $params);

        return is_null($key) ? $params : ($params[$key] ?? $default);
    }

    /**
     * @inheritDoc
     */
    public function set($uri): UrlFactoryContract
    {
        if (!$uri instanceof UriInterface || !$uri instanceof LeagueUri) {
            $uri = Http::createFromString($uri);
        }
        $this->uri = $uri;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function with(array $args): UrlFactoryContract
    {
        $this->without(array_keys($args));

        $this->uri = UriModifier::appendQuery($this->uri, Query::createFromParams($args));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function without(array $args): UrlFactoryContract
    {
        $this->uri = UriModifier::removeParams($this->uri, ...$args);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->uri ? (string)$this->uri : '';
    }
}