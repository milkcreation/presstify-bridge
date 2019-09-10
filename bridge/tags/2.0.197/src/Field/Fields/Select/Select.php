<?php declare(strict_types=1);

namespace tiFy\Field\Fields\Select;

use tiFy\Contracts\Field\{FieldFactory as FieldFactoryContract, Select as SelectContract};
use tiFy\Field\FieldFactory;

class Select extends FieldFactory implements SelectContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var string $name Clé d'indice de la valeur de soumission du champ.
     *      @var string $value Valeur courante de soumission du champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var string[]|array|SelectChoice[]|SelectChoices $choices Liste de selection d'éléments.
     *      @var bool $multiple Activation de la liste de selection multiple.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'    => [],
            'after'    => '',
            'before'   => '',
            'name'     => '',
            'value'    => null,
            'viewer'   => [],
            'choices'  => [],
            'multiple' => false
        ];
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        $value = $this->get('value', null);

        if (is_null($value)) {
            return null;
        }

        if (!is_array($value)) {
            $value = array_map('trim', explode(',', (string)$value));
        }

        $value = array_unique($value);

        if (!$this->get('multiple')) {
            $value = [reset($value)];
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldFactoryContract
    {
        parent::parse();

        $choices = $this->get('choices', []);
        if (!$choices instanceof SelectChoices) {
            $this->set('choices', new SelectChoices($choices, $this->getValue()));
        }

        if ($this->get('multiple')) {
            $this->push('attrs', 'multiple');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseName(): FieldFactoryContract
    {
        if ($name = $this->get('name')) {
            $this->set('attrs.name', $this->get('multiple') ? "{$name}[]" : $name);
        }

        return $this;
    }
}