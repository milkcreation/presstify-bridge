<?php declare(strict_types=1);

namespace tiFy\Field\Driver\RadioCollection;

use tiFy\Contracts\Field\{
    FieldDriver as FieldDriverContract,
    RadioCollection as RadioCollectionContract,
    RadioWalker as RadioWalkerContract
};
use tiFy\Field\FieldDriver;
use tiFy\Field\Driver\Radio\Radio;

class RadioCollection extends FieldDriver implements RadioCollectionContract
{
    /**
     * {@inheritDoc}
     *
     * @var array $attrs Attributs HTML du champ.
     * @var string $after Contenu placé après le champ.
     * @var string $before Contenu placé avant le champ.
     * @var string $name Clé d'indice de la valeur de soumission du champ.
     * @var string $value Valeur courante de soumission du champ.
     * @var string|array|bool $default Valeur de sélection par défaut. Aucune si false|La première si true|Valeur(s) par défaut.
     * @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * @var array|Radio[]|RadioChoice[]|RadioWalker $choices
     */
    public function defaults(): array
    {
        return [
            'attrs'   => [],
            'after'   => '',
            'before'  => '',
            'name'    => '',
            'value'   => null,
            'default' => false,
            'viewer'  => [],
            'choices' => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldDriverContract
    {
        parent::parse();

        $choices = $this->get('choices', []);
        if (!$choices instanceof RadioWalkerContract) {
            $choices = new RadioWalker($choices);
        }
        $this->set('choices', $choices->setField($this)->build());

        return $this;
    }
}