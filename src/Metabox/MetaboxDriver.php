<?php declare(strict_types=1);

namespace tiFy\Metabox;

use Closure;
use tiFy\Contracts\Metabox\{MetaboxContext, MetaboxDriver as MetaboxDriverContract, MetaboxManager, MetaboxScreen};
use tiFy\Contracts\View\PlatesEngine;
use tiFy\Support\{Arr, ParamsBag, Proxy\View};

class MetaboxDriver extends ParamsBag implements MetaboxDriverContract
{
    /**
     * Alias de qualification.
     * @var string
     */
    protected $alias = '';

    /**
     * Liste des arguments dynamiques passée en paramètres à l'appel du rendu.
     * @var array
     */
    protected $args = [];

    /**
     * Instance du contexte d'affichage.
     * @var MetaboxContext|null
     */
    protected $context;

    /**
     * Liste de fonction anonyme de
     * @var Closure[]
     */
    protected $handlers = [];

    /**
     * Instance du gestionnaire de metaboxes.
     * @var MetaboxManager|null
     */
    protected $manager;

    /**
     * Instance de l'écran d'affichage.
     * @var MetaboxScreen|null
     */
    protected $screen;

    /**
     * Valeur courante.
     * @var mixed
     */
    protected $value;

    /**
     * Instance du moteur d'affichage des gabarits.
     * @var PlatesEngine
     */
    protected $viewer;

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
    public function alias(): string
    {
        return $this->alias ?: class_info($this)->getKebabName();
    }

    /**
     * @inheritDoc
     */
    public function boot(): void { }

    /**
     * @inheritDoc
     */
    public function context(): ?MetaboxContext
    {
        return $this->context;
    }

    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'content'  => '',
            'name'     => '',
            'parent'   => null,
            'params'   => [],
            'position' => null,
            'title'    => '',
            'value'    => null,
            'viewer'   => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function handle($args): MetaboxDriverContract
    {
        $this->args = $args;

        foreach ($this->handlers as $handler) {
            $handler($this, ...$this->args);
        }

        $value = $this->get('value');
        $this->value = ($value instanceof Closure) ? $value($this, ...$this->args) : $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function manager(): ?MetaboxManager
    {
        return $this->manager;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return $this->get('name');
    }

    /**
     * @inheritDoc
     */
    public function params($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->get('params', []);
        } elseif (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set("params.{$k}", $v);
            }

            return $this;
        } else {
            return $this->get("params.{$key}", $default);
        }
    }

    /**
     * @inheritDoc
     */
    public function parse(): ?MetaboxDriverContract
    {
        $this->attributes = array_merge(
            $this->defaults(), config("metabox.driver.{$this->alias}", []), $this->attributes
        );

        $this->params(array_merge($this->defaultParams(), $this->get('params', [])));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->viewer()->exists('index')
            ? (string)$this->viewer('index', $this->all()) : $this->get('content', '');
    }

    /**
     * @inheritDoc
     */
    public function screen(): ?MetaboxScreen
    {
        return $this->screen;
    }

    /**
     * @inheritDoc
     */
    public function setContext(string $name): MetaboxDriverContract
    {
        $this->context = $this->manager()->getContext($name);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setHandler(Closure $func): MetaboxDriverContract
    {
        $this->handlers[] = $func;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setManager(MetaboxManager $manager): MetaboxDriverContract
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setScreen(string $name): MetaboxDriverContract
    {
        $this->screen = $this->manager()->getScreen($name) ?: $this->manager()->addScreen($name);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return $this->viewer()->exists('title')
            ? (string)$this->viewer('title', $this->all()) : $this->get('title', '');
    }

    /**
     * @inheritDoc
     */
    public function value(?string $key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->value;
        } elseif (is_array($this->value)) {
            return Arr::get($this->value, $key, $default);
        } else {
            return $default;
        }
    }

    /**
     * @inheritDoc
     */
    public function viewer(?string $view = null, array $data = [])
    {
        if (!$this->viewer) {
            $this->viewer = View::getPlatesEngine(array_merge([
                'directory' => $this->manager()->resourcesDir("/views/driver/{$this->alias()}"),
                'factory'   => MetaboxView::class,
                'metabox'   => $this
            ], config('metabox.viewer', []), $this->get('viewer', [])));
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->render($view, $data);
    }
}