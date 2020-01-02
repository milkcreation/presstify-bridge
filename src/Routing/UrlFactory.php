<?php declare(strict_types=1);

namespace tiFy\Routing;

use Psr\Http\Message\UriInterface;
use League\Uri\{
    Components\Query,
    Http,
    Modifiers\AppendQuery,
    Modifiers\AppendSegment,
    Modifiers\RemoveQueryParams,
    UriInterface as LeagueUri,
};
use tiFy\Contracts\Routing\UrlFactory as UrlFactoryContract;

class UrlFactory implements UrlFactoryContract
{
    /**
     * Instance de l'url.
     * @var LeagueUri|UriInterface
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
        return (string)$this->get();
    }

    /**
     * @inheritDoc
     */
    public function appendSegment(string $segment): UrlFactoryContract
    {
        $this->uri = (new AppendSegment($segment))->process($this->uri);

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
    public function decoded(bool $raw = true)
    {
        return $raw ? rawurldecode((string)$this->uri) : urldecode((string)$this->uri);
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
        $this->uri = (new AppendQuery((string)Query::createFromPairs($args)))->process($this->uri);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function without(array $args): UrlFactoryContract
    {
        $this->uri = (new RemoveQueryParams($args))->process($this->uri);

        return $this;
    }
}