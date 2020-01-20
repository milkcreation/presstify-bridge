<?php declare(strict_types=1);

namespace tiFy\Routing;

use tiFy\Contracts\{Http\Request, Routing\Router, Routing\Url as UrlContract, Routing\UrlFactory as UrlFactoryContract};

class Url extends UrlFactory implements UrlContract
{
    /**
     * Instance des requêtes HTTP.
     * @var Router
     */
    protected $request;

    /**
     * Sous arborescence du chemin de l'url racine.
     * @var string
     */
    protected $rewriteBase;

    /**
     * Instance du routage.
     * @var Router
     */
    protected $router;

    /**
     * CONSTRUCTEUR
     *
     * @param Router $router
     * @param Request $request
     *
     * @return void
     */
    public function __construct(Router $router, Request $request)
    {
        $this->router = $router;
        $this->request = $request;

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
        $root = (string)$this->root();

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