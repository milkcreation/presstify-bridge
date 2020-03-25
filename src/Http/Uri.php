<?php declare(strict_types=1);

namespace tiFy\Http;

use League\Uri\Http;
use tiFy\Contracts\Http\{Request, Uri as UriContract};

class Uri extends Http implements UriContract
{
    /**
     * Instance de la requête associée.
     * @var Request|null
     */
    protected $request;

    /**
     * @inheritDoc
     */
    public static function createFromRequest(Request $request): UriContract
    {
        $args = [
            $request->getScheme(),
            $request->getUser(),
            $request->getPassword(),
            $request->getHttpHost(),
            $request->getPort(),
            $request->getBasePath() . '/' . $request->path(),
            $request->getQueryString()
        ];

        return (new static(...$args))->setRequest($request);
    }

    /**
     * @inheritDoc
     */
    public function request(): ?Request
    {
        return $this->request;
    }

    /**
     * @inheritDoc
     */
    public function getSchemeAndHttpHost(): string
    {
        return $this->getScheme().'://'.$this->getHost();
    }

    /**
     * @inheritDoc
     */
    public function getRelativeUriFromUrl(string $url, $base = true): ?string
    {
        $pattern = $this->getSchemeAndHttpHost();
        if (!$base && $this->request()) {
            $pattern .= $this->request()->getBaseUrl();
        }

        return preg_match('/^' . preg_quote($pattern, '/') .'(.*)/', $url, $matches)
            ? $matches[1] : null;
    }

    /**
     * @inheritDoc
     */
    public function setRequest(Request $request): UriContract
    {
        $this->request = $request;

        return $this;
    }
}