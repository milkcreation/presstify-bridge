<?php declare(strict_types=1);

namespace tiFy\Field\Fields\RadioCollection;

use tiFy\Contracts\Field\{FieldFactory as FieldFactoryContract, RadioCollection as RadioCollectionContract};
use tiFy\Field\FieldFactory;
use tiFy\Field\Fields\Radio\Radio;

class RadioCollection extends FieldFactory implements RadioCollectionContract
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
    public function parse(): FieldFactoryContract
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