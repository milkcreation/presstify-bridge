<?php declare(strict_types=1);

namespace tiFy\Field\Driver\Radio;

use tiFy\Contracts\Field\{FieldDriver as FieldDriverContract, Radio as RadioContract};
use tiFy\Field\FieldDriver;

class Radio extends FieldDriver implements RadioContract
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
     *      @var bool|null $checked Activation de la selection.
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
            'checked' => false
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldDriverContract
    {
        parent::parse();

        $this->set('attrs.type', 'radio');

        if ($this->isChecked()) {
            $this->set('attrs.checked', 'checked');
        }

        return $this;
    }
}