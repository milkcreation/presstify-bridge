<?php declare(strict_types=1);

namespace tiFy\Template;

use Psr\Http\Message\ServerRequestInterface;
use tiFy\Contracts\Template\{
    FactoryActions,
    FactoryAssets,
    FactoryCache,
    FactoryDb,
    FactoryBuilder,
    FactoryNotices,
    FactoryRequest,
    FactoryServiceProvider,
    FactoryUrl,
    TemplateFactory as TemplateFactoryContract,
    TemplateManager as TemplateManagerContract};
use tiFy\Support\ParamsBag;
use tiFy\Support\Str;

class TemplateFactory extends ParamsBag implements TemplateFactoryContract
{
    /**
     * Liste des instances de template déclaré.
     * @var TemplateFactoryContract[]
     */
    private static $instance = [];

    /**
     * Instance du gestionnaire de templates.
     * @var TemplateManager
     */
    protected $manager;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Indicateur de préparation du template.
     * @var boolean
     */
    protected $prepared = false;

    /**
     * Identifiant de qualification compatible au formatage dans une url.
     * @var string|null
     */
    protected $slug;

    /**
     * Liste des fournisseurs de services.
     * @var string[]
     */
    protected $serviceProviders = [
        Factory\ServiceProvider::class
    ];

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        $this->prepare();

        return (string)$this->render();
    }

    /**
     * @inheritDoc
     */
    public function actions(): FactoryActions
    {
        return $this->resolve('actions');
    }

    /**
     * @inheritDoc
     */
    public function assets(): FactoryAssets
    {
        return $this->resolve('assets');
    }

    /**
     * @inheritDoc
     */
    public function baseUrl(bool $absolute = false): string
    {
        $path = $this->manager->baseUrl . '/' . $this->name();

        return $absolute ? (string)url()->root($path) : url()->rewriteBase() . '/' . $path;
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {

    }

    /**
     * @inheritDoc
     */
    public function bound(string $abstract)
    {
        return $this->manager->getContainer()->has("template.factory.{$this->name()}.{$abstract}");
    }

    /**
     * @inheritDoc
     */
    public function builder(): FactoryBuilder
    {
        return $this->resolve('builder');
    }

    /**
     * @inheritDoc
     */
    public function cache(): FactoryCache
    {
        return $this->resolve('cache');
    }

    /**
     * @inheritDoc
     */
    public function db(): ?FactoryDb
    {
        return $this->resolve('db');
    }

    /**
     * @inheritDoc
     */
    public function display()
    {
        echo (string)$this->render();
    }

    /**
     * @inheritDoc
     */
    public function getServiceProviders()
    {
        return $this->serviceProviders;
    }

    /**
     * @inheritDoc
     */
    public function httpCacheController(string $path, ServerRequestInterface $psrRequest)
    {
        return $cache = $this->cache() ? $this->cache()->getResponse($path, $psrRequest) : null;
    }

    /**
     * @inheritDoc
     */
    public function httpController(ServerRequestInterface $psrRequest)
    {
        return call_user_func($this->resolve('controller'), $psrRequest);
    }

    /**
     * @inheritDoc
     */
    public function httpXhrController(ServerRequestInterface $psrRequest)
    {
        return call_user_func($this->resolve('xhr'), $psrRequest);
    }

    /**
     * @inheritDoc
     */
    public function label($key = null, string $default = '')
    {
        $labels = $this->resolve('labels');

        if(is_string($key)) {
            return $labels->get($key, $default);
        }  elseif (is_array($key)) {
            return $labels->set($key);
        } else {
            return $labels;
        }
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function notices(): FactoryNotices
    {
        return $this->resolve('notices');
    }

    /**
     * @inheritDoc
     */
    public function param($key = null, $default = null)
    {
        $params = $this->resolve('params');

        if (is_null($key)) {
            return $params;
        } elseif (is_array($key)) {
            return $params->set($key);
        } else {
            return $params->get($key, $default);
        }
    }

    /**
     * @inheritDoc
     */
    public function prepare(): TemplateFactoryContract
    {
        if (!$this->prepared) {
            $this->proceed();
            $this->prepared = true;
            events()->trigger('template.factory.prepared', [$this->name(), &$this]);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function proceed(): TemplateFactoryContract
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function provider($alias)
    {
        return $this->get('providers', [])[$alias] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        return ($this->has('content'))
            ? call_user_func_array($this->get('content'), [&$this])
            : __('Aucun contenu à afficher', 'tify');
    }

    /**
     * @inheritDoc
     */
    public function request(): FactoryRequest
    {
        return $this->resolve('request');
    }

    /**
     * @inheritDoc
     */
    public function resolve(string $abstract, array $args = [])
    {
        return $this->manager->getContainer()->get("template.factory.{$this->name()}.{$abstract}", $args);
    }

    /**
     * @inheritDoc
     */
    public function setInstance(string $name, TemplateManagerContract $manager): TemplateFactoryContract
    {
        if (!isset(self::$instance[$name])) {
            self::$instance[$name] = $this;

            $this->name = $name;
            $this->manager = $manager;

            $this->boot();
            $this->parse();

            foreach ($this->getServiceProviders() as $serviceProvider) {
                $resolved = new $serviceProvider();

                if ($resolved instanceof FactoryServiceProvider) {
                    $resolved->setTemplateFactory($this)->setContainer($this->manager->getContainer());
                    $this->manager->getContainer()->addServiceProvider($resolved);
                }
            }
            events()->trigger('template.factory.boot', [$this->name(), &$this]);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function slug(): string
    {
        if (is_null($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
        return $this->slug;
    }

    /**
     * @inheritDoc
     */
    public function url(): FactoryUrl
    {
        return $this->resolve('url');
    }

    /**
     * @inheritDoc
     */
    public function viewer(?string $view = null, array $data = [])
    {
        $viewer = $this->resolve('viewer');

        if (func_num_args() === 0) {
            return $viewer;
        }

        return $viewer->render($view, $data);
    }
}