<?php declare(strict_types=1);

namespace tiFy\Field\Driver\Colorpicker;

use tiFy\Contracts\Field\{Colorpicker as ColorpickerContract, FieldDriver as FieldDriverContract};
use tiFy\Field\FieldDriver;

class Colorpicker extends FieldDriver implements ColorpickerContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     * @var array $attrs Attributs HTML du champ.
     * @var string $after Contenu placé après le champ.
     * @var string $before Contenu placé avant le champ.
     * @var string $name Clé d'indice de la valeur de soumission du champ.
     * @var string $value Valeur courante de soumission du champ.
     * @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * @var array $options {
     *          Liste des options du contrôleur ajax.
     * @see https://bgrins.github.io/spectrum/
     *      }
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'   => [],
            'after'   => '',
            'before'  => '',
            'name'    => '',
            'value'   => '',
            'viewer'  => [],
            'options' => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldDriverContract
    {
        parent::parse();

        $this->set([
            'attrs.data-control' => 'colorpicker',
            'attrs.data-options' => array_merge([
                'preferredFormat' => 'hex',
                'showInput'       => true,
            ], $this->get('options', [])),
        ]);

        return $this;
    }
}