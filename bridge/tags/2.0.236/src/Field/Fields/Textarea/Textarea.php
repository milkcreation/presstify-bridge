<?php declare(strict_types=1);

namespace tiFy\Field\Fields\Textarea;

use tiFy\Contracts\Field\{FieldFactory as FieldFactoryContract, Textarea as TextareaContract};
use tiFy\Field\FieldFactory;

class Textarea extends FieldFactory implements TextareaContract
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
            'viewer' => []
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldFactoryContract
    {
        parent::parse();

        $this->set('content', $this->get('value'));

        return $this;
    }
}