<?php declare(strict_types=1);

namespace tiFy\Field\Driver\RadioCollection;

use tiFy\Contracts\Field\{FieldDriver as FieldDriverContract, RadioCollection as RadioCollectionContract};
use tiFy\Field\FieldDriver;
use tiFy\Field\Driver\Radio\Radio;

class RadioCollection extends FieldDriver implements RadioCollectionContract
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
     *      @var array|Radio[]|RadioChoice[]|RadioChoices $choices
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
        if (!$choices instanceof RadioChoices) {
            $choices = new RadioChoices($choices, $this->getName(), $this->getValue());
        }
        $this->set('choices', $choices->setField($this));

        return $this;
    }
}