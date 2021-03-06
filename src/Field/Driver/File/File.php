<?php declare(strict_types=1);

namespace tiFy\Field\Driver\File;

use tiFy\Contracts\Field\{FieldDriver as FieldDriverContract, File as FileContract};
use tiFy\Field\FieldDriver;

class File extends FieldDriver implements FileContract
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
            'attrs'    => [],
            'after'    => '',
            'before'   => '',
            'name'     => '',
            'value'    => '',
            'viewer'   => [],
            'multiple' => false
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldDriverContract
    {
        parent::parse();

        $this->set('attrs.type', 'file');
        if ($this->get('multiple')) {
            $this->push('attrs', 'multiple');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseAttrName(): FieldDriverContract
    {
        if ($name = $this->get('name')) {
            if ($this->get('multiple', false)) {
                $name = "{$name}[]";
            }

            $this->set('attrs.name', $name);
        }

        return $this;
    }
}