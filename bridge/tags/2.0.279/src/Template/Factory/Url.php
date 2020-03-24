<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use tiFy\Contracts\Template\FactoryUrl as FactoryUrlContract;
use tiFy\Contracts\Template\TemplateFactory;
use tiFy\Routing\Url as BaseUrl;

class Url extends BaseUrl implements FactoryUrlContract
{
    use FactoryAwareTrait;

    /**
     * Url des contrôle.
     * @var string
     */
    protected $baseUrl;

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
        parent::__construct(router(), request());
    }

    /**
     * @inheritDoc
     */
    public function http(bool $absolute = false): string
    {
        return $absolute ? (string)$this->root($this->baseUrl) : $this->rewriteBase() . '/' . $this->baseUrl;
    }

    /**
     * @inheritDoc
     */
    public function setBaseUrl(string $base_url): FactoryUrlContract
    {
        $this->baseUrl = $base_url;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function xhr(bool $absolute = false): string
    {
        $path = $this->baseUrl . '/xhr';

        return $absolute ? (string)$this->root($path) : $this->rewriteBase() . '/' . $path;
    }
}