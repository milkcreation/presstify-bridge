<?php declare(strict_types=1);

namespace tiFy\Field\Driver\Text;

use tiFy\Contracts\Field\{FieldDriver as FieldDriverContract, Text as TextContract};
use tiFy\Field\FieldDriver;

class Text extends FieldDriver implements TextContract
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
    public function parse(): FieldDriverContract
    {
        parent::parse();

        if (!$this->get('attrs.type')) {
            $this->set('attrs.type', 'text');
        }

        return $this;
    }
}