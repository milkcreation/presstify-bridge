<?php declare(strict_types=1);

namespace tiFy\Template;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use tiFy\Contracts\Container\Container;
use tiFy\Contracts\Template\{TemplateFactory as TemplateFactoryContract, TemplateManager as TemplateManagerContract};
use tiFy\Support\Manager;
use tiFy\Support\Proxy\Router;

class TemplateManager extends Manager implements TemplateManagerContract
{
    /**
     * Url de base de routage.
     * @var string
     */
    public $baseUrl = '';

    /**
     * Liste des éléments déclarés.
     * @var TemplateFactoryContract[]
     */
    protected $items = [];

    /**
     * {@inheritDoc}
     *
     * @return TemplateFactoryContract|null
     */
    public function get(...$args): ?TemplateFactoryContract
    {
        return parent::get($args[0]);
    }

    /**
     * {@inheritDoc}
     *
     * @return Container|null
     */
    public function getContainer(): ?ContainerInterface
    {
        return parent::getContainer();
    }

    /**
     * @inheritDoc
     */
    public function httpCacheController(string $name, string $path, ServerRequestInterface $psrRequest)
    {
        return $this->get($name)->httpCacheController($path, $psrRequest);
    }

    /**
     * @inheritDoc
     */
    public function httpController(string $name, ServerRequestInterface $psrRequest)
    {
        return $this->get($name)->httpController($psrRequest);
    }

    /**
     * @inheritDoc
     */
    public function httpXhrcontroller(string $name, ServerRequestInterface $psrRequest)
    {
        return $this->get($name)->httpXhrController($psrRequest);
    }

    /**
     * @inheritDoc
     */
    public function prepareRoutes(): TemplateManagerContract
    {
        $this->baseUrl = md5('tify:template');

        foreach(['head', 'delete', 'get', 'options', 'post', 'put', 'patch'] as $method) {
            Router::$method($this->baseUrl . '/{name}', [$this, 'httpController']);
            Router::xhr($this->baseUrl . '/{name}/xhr', [$this, 'httpXhrController'], $method);
        }
        Router::get($this->baseUrl . '/{name}/cache/{path:.*}', [$this, 'httpCacheController'])->strategy('app');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function register($name, ...$args): TemplateManagerContract
    {
        return $this->set([$name => $args[0] ?? []]);
    }

    /**
     * @inheritDoc
     */
    public function resourcesDir(?string $path = ''): ?string
    {
        $path = $path ? '/Resources/' . ltrim($path, '/') : '/Resources';

        return file_exists(__DIR__ . $path) ? __DIR__ . $path : '';
    }

    /**
     * @inheritDoc
     */
    public function resourcesUrl(?string $path = ''): ?string
    {
        $cinfo = class_info($this);
        $path = '/Resources/' . ltrim($path, '/');

        return file_exists($cinfo->getDirname() . $path) ? class_info($this)->getUrl() . $path : '';
    }

    /**
     * @inheritDoc
     */
    public function walk(&$item, $key = null): void
    {
        if (!$item instanceof TemplateFactory) {
            $attrs = $item;
            /* @var TemplateFactoryContract: $item */
            $item = $this->getContainer()->get(TemplateFactoryContract::class);
            $item->set($attrs);
        }
        $item->setInstance((string)$key, $this);
    }
}