<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use tiFy\Contracts\Template\FactoryUrl as FactoryUrlContract;
use tiFy\Contracts\Template\TemplateFactory;
use tiFy\Routing\Url as BaseUrl;
use tiFy\Support\Proxy\{Router, Request};

class Url extends BaseUrl implements FactoryUrlContract
{
    use FactoryAwareTrait;

    /**
     * Url des contrôleurs.
     * @var string
     */
    protected $basePath = '';

    /**
     * Url d'affichage du template.
     * @var string
     */
    protected $displayUrl = '';

    /**
     * Instance du gabarit d'affichage.
     * @var TemplateFactory
     */
    protected $factory;

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(Router::getInstance(), Request::getInstance());
    }

    /**
     * @inheritDoc
     */
    public function action(bool $absolute = false): string
    {
        return $this->factory->ajax() ? $this->xhr($absolute) : $this->http($absolute);
    }

    /**
     * @inheritDoc
     */
    public function display(): string
    {
        return $this->displayUrl;
    }

    /**
     * @inheritDoc
     */
    public function http(bool $absolute = false): string
    {
        return $absolute ? (string)$this->root($this->basePath) : $this->rewriteBase() . '/' . $this->basePath;
    }

    /**
     * @inheritDoc
     */
    public function setBasePath(string $base_path): FactoryUrlContract
    {
        $this->basePath = $base_path;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDisplayUrl(string $display_url): FactoryUrlContract
    {
        $this->displayUrl = $display_url;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function xhr(bool $absolute = false): string
    {
        $path = $this->basePath . '/xhr';

        return $absolute ? (string)$this->root($path) : $this->rewriteBase() . '/' . $path;
    }
}
