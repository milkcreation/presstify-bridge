<?php declare(strict_types=1);

namespace tiFy\Field\Fields\Select;

use tiFy\Contracts\Field\SelectChoice as SelectChoiceContract;
use tiFy\Support\HtmlAttrs;
use tiFy\Support\ParamsBag;

class SelectChoice extends ParamsBag implements SelectChoiceContract
{
    /**
     * Nom de qualification.
     * @var int|string
     */
    protected $name = '';

    /**
     * Niveau de profondeur d'affichage dans le selecteur.
     * @var int
     */
    private $depth = 0;

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification de l'élément.
     * @param array|string $attrs Liste des attributs de configuration|Intitulé de qualification de l'option.
     *
     * @return void
     */
    public function __construct($name, $attrs = [])
    {
        $this->name = $name;

        $this->set($attrs);
    }

    /**
     * @inheritdoc
     */
    public function defaults(): array
    {
        return [
            'name'      => $this->name,
            'group'     => false,
            'attrs'     => [],
            'parent'    => null,
            'value'     => $this->name,
            'content'   => ''
        ];
    }

    /**
     * @inheritdoc
     */
    public function getContent(): string
    {
        return (string)$this->get('content');
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return (string)$this->get('name', '');
    }

    /**
     * @inheritdoc
     */
    public function getValue(): string
    {
        return (string)$this->get('value');
    }

    /**
     * @inheritdoc
     */
    public function getParent(): ?string
    {
        return $this->get('parent', null);
    }

    /**
     * @inheritdoc
     */
    public function hasParent(): bool
    {
        return !is_null($this->get('parent'));
    }

    /**
     * @inheritdoc
     */
    public function isDisabled(): bool
    {
        return in_array('disabled', $this->get('attrs', []));
    }

    /**
     * @inheritdoc
     */
    public function isGroup(): bool
    {
        return $this->get('group');
    }

    /**
     * @inheritdoc
     */
    public function isSelected(): bool
    {
        return !$this->isGroup() && in_array('selected', $this->get('attrs', []), true);
    }

    /**
     * @inheritdoc
     */
    public function setDepth(int $depth = 0): SelectChoiceContract
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setSelected(array $selected): SelectChoiceContract
    {
        if (!is_null($selected)) {
            if (!$this->isGroup() && in_array($this->getValue(), $selected)) {
                $this->push('attrs', 'selected');
            }
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function parse(): SelectChoiceContract
    {
        parent::parse();

        if ($this->isGroup()) {
            $this->pull('value');
            $this->set(
                'attrs.label',
                str_repeat("&nbsp;&nbsp;&nbsp;", $this->depth) . htmlentities($this->pull('content'))
            );
        } else {
            $this->set('attrs.value', $this->getValue());
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function tagClose(): string
    {
        return $this->isGroup() ? "\n" . str_repeat("\t", $this->depth) . "</optgroup>" : "</option>";
    }

    /**
     * @inheritdoc
     */
    public function tagContent(): string
    {
        return $this->getContent() ? $this->getContent() : '';
    }

    /**
     * @inheritdoc
     */
    public function tagOpen(): string
    {
        $attrs = HtmlAttrs::createFromAttrs($this->get('attrs', []));

        return "\n" . str_repeat("\t", $this->depth) . ($this->isGroup() ? "<optgroup {$attrs}>" : "<option {$attrs}>");
    }
}