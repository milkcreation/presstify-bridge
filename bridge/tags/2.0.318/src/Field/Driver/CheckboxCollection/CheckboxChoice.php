<?php declare(strict_types=1);

namespace tiFy\Field\Driver\CheckboxCollection;

use tiFy\Contracts\Field\{
    Checkbox as CheckboxContract,
    CheckboxChoice as CheckboxChoiceContract,
    CheckboxWalker as CheckboxWalkerContract,
    Label as LabelContract
};
use tiFy\Support\{ParamsBag, Proxy\Field};
use tiFy\Field\Driver\{Checkbox\Checkbox, Label\Label};

class CheckboxChoice extends ParamsBag implements CheckboxChoiceContract
{
    /**
     * Compteur d'indice.
     * @var integer
     */
    static $_index = 0;

    /**
     * Indicateur d'initialisation.
     * @var bool
     */
    private $built = false;

    /**
     * Instance de la case à cocher.
     * @var Checkbox
     */
    protected $checkbox;

    /**
     * Identifiant de qualification.
     * @var string|int
     */
    protected $id = '';

    /**
     * Indice de qualification.
     * @var integer
     */
    protected $index = 0;

    /**
     * Instance de l'intitulé.
     * @var Label
     */
    protected $label;

    /**
     * Instance du gestionnaire d'affichage de la liste des éléments.
     * @var CheckboxWalkerContract
     */
    protected $walker;

    /**
     * CONSTRUCTEUR.
     *
     * @param string|int $id Identifiant de qualification.
     * @param array|string $attrs Liste des attributs de configuration.
     *
     * @return void
     */
    public function __construct($id, $attrs)
    {
        $this->id = $id;
        $this->index = self::$_index++;

        if (is_string($attrs)) {
            $attrs = [
                'label' => [
                    'content' => $attrs
                ]
            ];
        }

        if ($attrs instanceof Checkbox) {
            $this->checkbox = $attrs;
        } else {
            $this->set($attrs);
        }
    }

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
    public function build(): CheckboxChoiceContract
    {
        if(!$this->built) {
            $this->parse();

            $this->built = true;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'label'     => [
                'before'       => '',
                'after'        => '',
                'content'      => '',
                'attrs'        => []
            ],
            'checkbox'  => [
                'before'  => '',
                'after'   => '',
                'attrs'   => [],
                'name'    => '',
                'value'   => '',
                'checked' => $this->id
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function getCheckbox(): CheckboxContract
    {
        return $this->checkbox;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): LabelContract
    {
        return $this->label;
    }

    /**
     * @inheritDoc
     */
    public function getNameAttr(): string
    {
        return $this->getCheckbox() instanceof Checkbox ? $this->getCheckbox()->getName() : '';
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->getCheckbox() instanceof Checkbox ? $this->getCheckbox()->getValue() : null;
    }

    /**
     * @inheritDoc
     */
    public function isChecked(): bool
    {
        return $this->getCheckbox() instanceof Checkbox ? $this->getCheckbox()->isChecked() : false;
    }

    /**
     * @inheritDoc
     */
    public function parse(): CheckboxChoiceContract
    {
        parent::parse();

        if (!$this->get('attrs.id')) {
            $this->set('attrs.id', 'FieldCheckboxCollection-item--' . $this->index);
        }

        if (!$this->get('checkbox.attrs.id')) {
            $this->set('checkbox.attrs.id', 'FieldCheckboxCollection-itemInput--'. $this->index);
        }

        if (!$this->get('checkbox.attrs.class')) {
            $this->set('checkbox.attrs.class', 'FieldCheckboxCollection-itemInput');
        }

        if (!$this->get('label.attrs.id')) {
            $this->set('label.attrs.id', 'FieldCheckboxCollection-itemLabel--'. $this->index);
        }

        if (!$this->get('label.attrs.class')) {
            $this->set('label.attrs.class', 'FieldCheckboxCollection-itemLabel');
        }

        if (!$this->get('label.attrs.for')) {
            $this->set('label.attrs.for', 'FieldCheckboxCollection-itemInput--'. $this->index);
        }

        $this->checkbox = Field::get('checkbox', $this->get('checkbox', []));
        $this->label = Field::get('label', $this->get('label', []));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->getCheckbox()->render() . $this->getLabel()->render();
    }

    /**
     * @inheritDoc
     */
    public function setNameAttr(string $name): CheckboxChoiceContract
    {
        if($this->getCheckbox() instanceof Checkbox) {
            $this->getCheckbox()->set('attrs.name', $name);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setChecked(): CheckboxChoiceContract
    {
        if($this->getCheckbox() instanceof Checkbox) {
            $this->getCheckbox()->push('attrs', 'checked');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setWalker(CheckboxWalkerContract $walker): CheckboxChoiceContract
    {
        $this->walker = $walker;

        return $this;
    }
}