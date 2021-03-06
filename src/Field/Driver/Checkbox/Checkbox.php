<?php declare(strict_types=1);

namespace tiFy\Field\Driver\Checkbox;

use tiFy\Contracts\Field\{Checkbox as CheckboxContract, FieldDriver as FieldDriverContract};
use tiFy\Field\FieldDriver;

class Checkbox extends FieldDriver implements CheckboxContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     * @var array $attrs Attributs HTML du champ.
     * @var string $after Contenu placé après le champ.
     * @var string $before Contenu placé avant le champ.
     * @var string $name Clé d'indice de la valeur de soumission du champ.
     * @var string $value Valeur courante de soumission du champ.
     * @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * @var null|bool $checked Activation de la selection.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'   => [],
            'after'   => '',
            'before'  => '',
            'name'    => '',
            'value'   => '',
            'viewer'  => [],
            'checked' => 'on',
        ];
    }

    /**
     * @inheritDoc
     */
    public function isChecked(): bool
    {
        $checked = $this->get('checked', false);

        if (is_bool($checked)) {
            return $checked;
        } elseif ($this->has('value')) {
            return in_array($checked, (array)$this->getValue());
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldDriverContract
    {
        parent::parse();

        $this->set('attrs.type', 'checkbox');

        if ($this->isChecked()) {
            $this->set('attrs.checked', 'checked');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseAttrValue(): FieldDriverContract
    {
        if (($value = $this->get('checked')) && !is_bool($value)) {
            $this->set('attrs.value', $value);
        }

        return $this;
    }
}