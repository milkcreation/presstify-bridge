<?php declare(strict_types=1);

namespace tiFy\Field\Driver\CheckboxCollection;

use tiFy\Contracts\Field\{
    CheckboxCollection as CheckboxCollectionContract,
    CheckboxWalker as CheckboxWalkerContract,
    FieldDriver as FieldDriverContract
};
use tiFy\Field\FieldDriver;
use tiFy\Field\Driver\Checkbox\Checkbox;

class CheckboxCollection extends FieldDriver implements CheckboxCollectionContract
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
     * @var array|Checkbox[]|CheckboxChoice[]|CheckboxWalker $choices Liste de choix.
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
        if (!$choices instanceof CheckboxWalkerContract) {
            $choices = new CheckboxWalker($choices);
        }
        $this->set('choices', $choices->setField($this)->build());

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @todo utiliser self::parseAttrName
     */
    public function getName(): string
    {
        $name = $this->get('attrs.name', '') ?: $this->get('name');

        return "{$name}[]";
    }
}