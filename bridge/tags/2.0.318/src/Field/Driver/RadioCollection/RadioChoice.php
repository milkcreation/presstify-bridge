<?php declare(strict_types=1);

namespace tiFy\Field\Driver\RadioCollection;

use tiFy\Contracts\Field\{
    Radio as RadioContract,
    RadioChoice as RadioChoiceContract,
    RadioWalker as RadioWalkerContract,
    Label as LabelContract
};
use tiFy\Support\{ParamsBag, Proxy\Field};
use tiFy\Field\Driver\{Label\Label, Radio\Radio};

class RadioChoice extends ParamsBag implements RadioChoiceContract
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
     * Nom de qualification.
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
     * Instance du bouton radio.
     * @var Radio
     */
    protected $radio;

    /**
     * Instance du gestionnaire d'affichage de la liste des éléments.
     * @var RadioWalkerContract
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
                ],
            ];
        }

        if ($attrs instanceof Radio) {
            $this->radio = $attrs;
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
    public function build(): RadioChoiceContract
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
            'label' => [
                'before'  => '',
                'after'   => '',
                'content' => '',
                'attrs'   => []
            ],
            'radio' => [
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
        return $this->getRadio() instanceof Radio ? $this->getRadio()->getName() : '';
    }

    /**
     * @inheritDoc
     */
    public function getRadio(): RadioContract
    {
        return $this->radio;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->getRadio() instanceof Radio ? $this->getRadio()->getValue() : null;
    }

    /**
     * @inheritDoc
     */
    public function isChecked(): bool
    {
        return $this->getRadio() instanceof Radio ? $this->getRadio()->isChecked() : false;
    }

    /**
     * @inheritDoc
     */
    public function parse(): RadioChoiceContract
    {
        parent::parse();

        if (!$this->get('attrs.id')) {
            $this->set('attrs.id', 'FieldRadioCollection-item--' . $this->index);
        }

        if (!$this->get('radio.attrs.id')) {
            $this->set('radio.attrs.id', 'FieldRadioCollection-itemInput--' . $this->index);
        }

        if (!$this->get('radio.attrs.class')) {
            $this->set('radio.attrs.class', 'FieldRadioCollection-itemInput');
        }

        if (!$this->get('label.attrs.id')) {
            $this->set('label.attrs.id', 'FieldRadioCollection-itemLabel--' . $this->index);
        }

        if (!$this->get('label.attrs.class')) {
            $this->set('label.attrs.class', 'FieldRadioCollection-itemLabel');
        }

        if (!$this->get('label.attrs.for')) {
            $this->set('label.attrs.for', 'FieldRadioCollection-itemInput--' . $this->index);
        }

        $this->radio = Field::get('radio', $this->get('radio', []));
        $this->label = Field::get('label', $this->get('label', []));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->getRadio()->render() . $this->getLabel()->render();
    }

    /**
     * @inheritDoc
     */
    public function setNameAttr(string $name): RadioChoiceContract
    {
        if ($this->getRadio() instanceof Radio) {
            $this->getRadio()->set('attrs.name', $name);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setChecked(): RadioChoiceContract
    {
        if ($this->getRadio() instanceof Radio) {
            $this->getRadio()->push('attrs', 'checked');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setWalker(RadioWalkerContract $walker): RadioChoiceContract
    {
        $this->walker = $walker;

        return $this;
    }
}