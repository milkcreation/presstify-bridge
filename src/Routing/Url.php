<?php declare(strict_types=1);

namespace tiFy\Routing;

use tiFy\Contracts\{
    Http\Request as RequestContract,
    Routing\Router as RouterContract,
    Routing\Url as UrlContract,
    Routing\UrlFactory as UrlFactoryContract
};
use tiFy\Http\Request;

class Url extends UrlFactory implements UrlContract
{
    /**
     * Instance des requêtes HTTP.
     * @var RequestContract
     */
    protected $request;

    /**
     * Sous arborescence du chemin de l'url racine.
     * @var string
     */
    protected $rewriteBase;

    /**
     * Instance du routage.
     * @var RouterContract
     */
    protected $router;

    /**
     * CONSTRUCTEUR
     *
     * @param RouterContract $router
     * @param RequestContract|null $request
     *
     * @return void
     */
    public function __construct(RouterContract $router, ?RequestContract $request = null)
    {
        $this->router = $router;
        $this->request = $request ?? Request::createFromGlobals();

        parent::__construct(app()->runningInConsole() ? (string)$this->root() : $this->request->fullUrl());
    }

    /**
     * @inheritDoc
     */
    public function current(bool $full = true): UrlFactoryContract
    {
        return new UrlFactory($full ? $this->request->fullUrl() : $this->request->url());
    }

    /**
     * @inheritDoc
     */
    public function rel(string $url): ?string
    {
        $root = $this->root()->render();

        return preg_match('/^' . preg_quote($root, '/') . '/', $url)
            ? '/'. ltrim(preg_replace('/^' . preg_quote($root, '/') . '/', '', $url), '/')
            : null;
    }

    /**
     * @inheritDoc
     */
    public function rewriteBase(): string
    {
        if (is_null($this->rewriteBase)) {
            $this->rewriteBase = preg_replace(
                '/^' . preg_quote($this->request->getSchemeAndHttpHost(), '/') . '/', '', $this->root()
            );
        }

        return $this->rewriteBase;
    }

    /**
     * @inheritDoc
     */
    public function root(string $path = ''): UrlFactoryContract
    {
        return new UrlFactory(config('app_url', $this->request->root()) . ($path ? '/' . ltrim($path, '/') : ''));
    }
}