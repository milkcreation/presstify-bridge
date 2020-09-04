<?php declare(strict_types=1);

namespace tiFy\Partial;

use Closure;
use tiFy\Contracts\Partial\{Partial as Manager, PartialDriver as PartialDriverContract};
use tiFy\Contracts\View\Engine as ViewEngine;
use tiFy\Support\{HtmlAttrs, ParamsBag};

abstract class PartialDriver extends ParamsBag implements PartialDriverContract
{
    /**
     * Liste des attributs par défaut.
     * @var array
     */
    protected static $defaults = [];

    /**
     * Indicateur d'initialisation.
     * @var string
     */
    private $booted = false;

    /**
     * Alias de qualification dans le gestionnaire.
     * @var string
     */
    private $alias = false;

    /**
     * Identifiant de qualification.
     * {@internal par défaut concaténation de l'alias et de l'indice.}
     * @var string
     */
    protected $id = '';

    /**
     * Indice de l'instance dans le gestionnaire.
     * @var int
     */
    protected $index = 0;

    /**
     * Instance du gestionnaire de portions d'affichage.
     * @var Manager
     */
    protected $manager;

    /**
     * Instance du moteur de gabarits d'affichage.
     * @var ViewEngine
     */
    protected $viewer;

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->render();
    }

    /**
     * @inheritDoc
     */
    public function after(): void
    {
        echo ($after = $this->get('after', '')) instanceof Closure ? call_user_func($after) : $after;
    }

    /**
     * @inheritDoc
     */
    public function attrs(): void
    {
        echo HtmlAttrs::createFromAttrs($this->get('attrs', []));
    }

    /**
     * @inheritDoc
     */
    public function before(): void
    {
        echo ($before = $this->get('before', '')) instanceof Closure ? call_user_func($before) : $before;
    }

    /**
     * @inheritDoc
     */
    public function boot(): void {}

    /**
     * @inheritDoc
     */
    public function content(): void
    {
        echo ($content = $this->get('content', '')) instanceof Closure ? call_user_func($content) : $content;
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function defaults(): array
    {
        return array_merge(static::$defaults, []);
    }

    /**
     * @inheritDoc
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @inheritDoc
     */
    public function manager(): ?Manager
    {
        return $this->manager;
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function parse(): PartialDriverContract
    {
        $this->attributes = array_merge(
            $this->defaults(), config("partial.driver.{$this->alias}", []), $this->attributes
        );

        $this->parseDefaults();

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function parseAttrClass(): PartialDriverContract
    {
        $base = ucfirst(preg_replace('/\./', '-', $this->getAlias()));

        $default_class = "{$base} {$base}--" . $this->getIndex();
        if (!$this->has('attrs.class')) {
            $this->set('attrs.class', $default_class);
        } else {
            $this->set('attrs.class', sprintf($this->get('attrs.class', ''), $default_class));
        }

        if (!$this->get('attrs.class')) {
            $this->forget('attrs.class');
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function parseAttrId(): PartialDriverContract
    {
        if (!$this->get('attrs.id')) {
            $this->forget('attrs.id');
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function parseDefaults(): PartialDriverContract
    {
        return $this->parseAttrId()->parseAttrClass()->parseViewer();
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function parseViewer(): PartialDriverContract
    {
        foreach($this->get('viewer', []) as $key => $value) {
            $this->viewer()->params([$key => $value]);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function prepare(string $alias, Manager $manager): PartialDriverContract
    {
        if (!$this->booted) {
            $this->alias = $alias;
            $this->manager = $manager;

            $this->boot();

            $this->booted = true;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return (string)$this->viewer('index', $this->all());
    }

    /**
     * @inheritDoc
     */
    public static function setDefaults(array $defaults = []): void
    {
        static::$defaults = $defaults;
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function setId(string $id): PartialDriverContract
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function setIndex(int $index): PartialDriverContract
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setViewer(ViewEngine $viewer): PartialDriverContract
    {
        $this->viewer = $viewer;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function viewer(?string $view = null, array $data = [])
    {
        if (is_null($this->viewer)) {
            $this->viewer = app()->get('partial.viewer', [$this]);
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->render($view, $data);
    }
}