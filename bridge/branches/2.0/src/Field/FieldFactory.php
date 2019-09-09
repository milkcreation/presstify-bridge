<?php declare(strict_types=1);

namespace tiFy\Field;

use Closure;
use tiFy\Contracts\Field\{Field as Manager, FieldFactory as FieldFactoryContract};
use tiFy\Contracts\View\ViewEngine;
use tiFy\Support\HtmlAttrs;
use tiFy\Support\Str;
use tiFy\Support\ParamsBag;

abstract class FieldFactory extends ParamsBag implements FieldFactoryContract
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
     * @return ViewEngine
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
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->get('attrs.name', '') ? : $this->get('name');
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->get('value', null);
    }

    /**
     * @inheritDoc
     */
    public function isChecked(): bool
    {
        $checked = $this->get('checked', false);

        if (is_bool($checked)) {
            return $checked;
        } elseif ($this->has('attrs.value')) {
            return in_array($checked, (array)$this->getValue());
        }

        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function parse(): FieldFactoryContract
    {
        parent::parse();

        $this->parseDefaults();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseDefaults(): FieldFactoryContract
    {
        $default_class = 'tiFyField-' . Str::camel($this->getAlias()) .
            ' tiFyField-' . Str::camel($this->getAlias()) . '--' . $this->getIndex();
        if (!$this->has('attrs.class')) {
            $this->set('attrs.class', $default_class);
        } else {
            $this->set('attrs.class', sprintf($this->get('attrs.class', ''), $default_class));
        }

        $this->parseName();
        $this->parseValue();
        $this->parseViewer();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseName(): FieldFactoryContract
    {
        if ($name = $this->get('name')) {
            $this->set('attrs.name', $name);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseValue(): FieldFactoryContract
    {
        if ($value = $this->get('value')) {
            $this->set('attrs.value', $value);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseViewer(): FieldFactoryContract
    {
        foreach($this->get('viewer', []) as $key => $value) {
            $this->viewer()->set($key, $value);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $alias, Manager $manager): FieldFactoryContract
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
    public function setId(string $id): FieldFactoryContract
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setIndex(int $index): FieldFactoryContract
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setViewer(ViewEngine $viewer): FieldFactoryContract
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
            $this->viewer = app()->get('field.viewer', [$this]);
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->make("_override::{$view}", $data);
    }
}