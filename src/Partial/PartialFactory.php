<?php declare(strict_types=1);

namespace tiFy\Partial;

use Closure;
use tiFy\Contracts\Partial\{Partial as Manager, PartialFactory as PartialFactoryContract};
use tiFy\Contracts\View\ViewEngine;
use tiFy\Support\{HtmlAttrs, ParamsBag, Str};

abstract class PartialFactory extends ParamsBag implements PartialFactoryContract
{
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
        return (string)$this->display();
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
    public function boot(): void
    {

    }

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
        return [];
    }

    /**
     * @inheritDoc
     */
    public function display(): string
    {
        return (string)$this->viewer($this->getAlias(), $this->all());
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
     * {@inheritDoc}
     *
     * @return $this
     */
    public function parse(): PartialFactoryContract
    {
        parent::parse();

        $this->parseDefaults();

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function parseAttrsClass(): PartialFactoryContract
    {
        $base = Str::studly($this->getAlias());

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
    public function parseAttrsId(): PartialFactoryContract
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
    public function parseDefaults(): PartialFactoryContract
    {
        return $this->parseAttrsId()->parseAttrsClass()->parseViewer();
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function parseViewer(): PartialFactoryContract
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
    public function prepare(string $alias, Manager $manager): PartialFactoryContract
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
     * {@inheritDoc}
     *
     * @return $this
     */
    public function setId(string $id): PartialFactoryContract
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function setIndex(int $index): PartialFactoryContract
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setViewer(ViewEngine $viewer): PartialFactoryContract
    {
        $this->viewer = $viewer;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function viewer($view = null, $data = [])
    {
        if (is_null($this->viewer)) {
            $this->viewer = app()->get('partial.viewer', [$this]);
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->make("_override::{$view}", $data);
    }
}