<?php declare(strict_types=1);

namespace tiFy\Field\Fields\Checkbox;

use tiFy\Contracts\Field\{Checkbox as CheckboxContract, FieldFactory as FieldFactoryContract};
use tiFy\Field\FieldFactory;

class Checkbox extends FieldFactory implements CheckboxContract
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
     *      @var null|bool $checked Activation de la selection.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'  => [],
            'after'  => '',
            'before' => '',
            'name'   => '',
            'value'  => '',
            'viewer' => [],
            'checked' => false,
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldFactoryContract
    {
        parent::parse();

        $this->set('attrs.type', 'checkbox');

        if ($this->isChecked()) {
            $this->set('attrs.checked', 'checked');
        }

        return $this;
    }
}