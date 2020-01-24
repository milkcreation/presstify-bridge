<?php declare(strict_types=1);

namespace tiFy\Field\Driver\CheckboxCollection;

use tiFy\Contracts\Field\{CheckboxCollection as CheckboxCollectionContract, FieldDriver as FieldDriverContract};
use tiFy\Field\FieldDriver;
use tiFy\Field\Driver\Checkbox\Checkbox;

class CheckboxCollection extends FieldDriver implements CheckboxCollectionContract
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
     *      @var array|Checkbox[]|CheckboxChoice[]|CheckboxChoices $choices Liste de choix.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'   => [],
            'after'   => '',
            'before'  => '',
            'name'    => '',
            'value'   => null,
            'viewer'  => [],
            'choices' => []
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldDriverContract
    {
        parent::parse();

        $choices = $this->get('choices', []);
        if (!$choices instanceof CheckboxChoices) {
            $choices = new CheckboxChoices($choices, $this->getName(), $this->getValue());
        }
        $this->set('choices', $choices->setField($this));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        $name = $this->get('attrs.name', '') ? : $this->get('name');

        return "{$name}[]";
    }
}