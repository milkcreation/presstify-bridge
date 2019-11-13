<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use Illuminate\Database\Eloquent\Model;
use tiFy\Contracts\Template\{
    FactoryActions as FactoryActionsContract,
    FactoryDb as FactoryDbContract,
    FactoryServiceProvider as FactoryServiceProviderContract,
    TemplateFactory};
use tiFy\Container\ServiceProvider as BaseServiceProvider;
use tiFy\View\ViewEngine;

class ServiceProvider extends BaseServiceProvider implements FactoryServiceProviderContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var TemplateFactory
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        events()->listen('template.factory.boot', function () {
            $this->registerFactories();
        }, 100);
    }

    /**
     * @inheritDoc
     */
    public function getFactoryAlias(string $alias): string
    {
        return "template.factory.{$this->factory->name()}.{$alias}";
    }

    /**
     * @inheritDoc
     */
    public function registerFactories(): void
    {
        $this->registerFactoryActions();
        $this->registerFactoryAssets();
        $this->registerFactoryBuilder();
        $this->registerFactoryCache();
        $this->registerFactoryDb();
        $this->registerFactoryHttpController();
        $this->registerFactoryHttpXhrController();
        $this->registerFactoryLabels();
        $this->registerFactoryParams();
        $this->registerFactoryNotices();
        $this->registerFactoryRequest();
        $this->registerFactoryUrl();
        $this->registerFactoryViewer();
    }

    /**
     * Déclaration du controleur des actions.
     *
     * @return void
     */
    public function registerFactoryActions(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('assets'), function (): FactoryActionsContract {
            return (new Actions())->setTemplateFactory($this->factory);
        });
    }

    /**
     * Déclaration du controleur des assets.
     *
     * @return void
     */
    public function registerFactoryAssets(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('assets'), function () {
            return (new Assets())->setTemplateFactory($this->factory);
        });
    }

    /**
     * Déclaration du controleur de construction de requête.
     *
     * @return void
     */
    public function registerFactoryBuilder(): void
    {
        $this->getContainer()->add($this->getFactoryAlias('builder'), function () {
            $attrs = $this->factory->param('query_args', []);

            $ctrl =  $this->factory->db() ? new DbBuilder() : new Builder();

            return $ctrl->setTemplateFactory($this->factory)->set(is_array($attrs) ? $attrs : []);
        });
    }

    /**
     * Déclaration du controleur de cache.
     *
     * @return void
     */
    public function registerFactoryCache(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('cache'), function () {
            return (new Cache())->setTemplateFactory($this->factory);
        });
    }

    /**
     * Déclaration du controleur de base de données.
     *
     * @return void
     */
    public function registerFactoryDb(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('db'), function (): ?FactoryDbContract {
            if ($db = $this->factory->provider('db')) {
                if ($db instanceof Model) {
                    $db = (new Db())->setDelegate($db);
                } elseif (!$db instanceof FactoryDbContract) {
                    $db = new Db();
                }

                $instance =  $db->setTemplateFactory($this->factory);

                return $instance;
            } else {
                return null;
            }
        });
    }

    /**
     * Déclaration du controleur de requête HTTP.
     *
     * @return void
     */
    public function registerFactoryHttpController(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('controller'), function () {
            return (new HttpController())->setTemplateFactory($this->factory);
        });
    }

    /**
     * Déclaration du controleur de requête HTTP XHR.
     *
     * @return void
     */
    public function registerFactoryHttpXhrController(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('xhr'), function () {
            return (new HttpXhrController())->setTemplateFactory($this->factory);
        });
    }

    /**
     * Déclaration du controleur d'intitulés.
     *
     * @return void
     */
    public function registerFactoryLabels(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('labels'), function () {
            return (new Labels())->setTemplateFactory($this->factory)
                ->setName($this->factory->name())
                ->set($this->factory->get('labels', []))
                ->parse();
        });
    }

    /**
     * Déclaration du controleur de messages de notification.
     *
     * @return void
     */
    public function registerFactoryNotices(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('notices'), function () {
            return (new Notices())->setTemplateFactory($this->factory);
        });
    }

    /**
     * Déclaration du controleur de paramètres.
     *
     * @return void
     */
    public function registerFactoryParams(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('params'), function () {
            $attrs = $this->factory->get('params', []);

            return (new Params())->setTemplateFactory($this->factory)
                ->set(is_array($attrs) ? $attrs : [])->parse();
        });
    }

    /**
     * Déclaration du controleur de messages de requête HTTP.
     *
     * @return void
     */
    public function registerFactoryRequest(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('request'), function () {
            return Request::capture()->setTemplateFactory($this->factory);
        });
    }

    /**
     * Déclaration du controleur des urls.
     *
     * @return void
     */
    public function registerFactoryUrl(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('url'), function () {
            return (new Url())->setTemplateFactory($this->factory);
        });
    }

    /**
     * Déclaration du controleur de gabarit d'affichage.
     *
     * @return void
     */
    public function registerFactoryViewer(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('viewer'), function () {
            $params = $this->factory->get('viewer', []);

            if (!$params instanceof ViewEngine) {
                $viewer = new ViewEngine(array_merge([
                    'directory' => template()->resourcesDir('/views')
                ], $params));

                $viewer->setController(Viewer::class);

                if (!$viewer->getOverrideDir()) {
                    $viewer->setOverrideDir(template()->resourcesDir('/views'));
                }
            } else {
                $viewer = $params;
            }

            $viewer->set('factory', $this->factory);

            return $viewer;
        });
    }
}