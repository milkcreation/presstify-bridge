<?php declare(strict_types=1);

namespace tiFy\Field\Driver\Datepicker;

use tiFy\Contracts\Field\{Datepicker as DatepickerContract, FieldDriver as FieldDriverContract};
use tiFy\Field\FieldDriver;

class Datepicker extends FieldDriver implements DatepickerContract
{
    /**
     * {@inheritDoc}
     *
     * @var array $attrs Attributs HTML du champ.
     * @var string $after Contenu placé après le champ.
     * @var string $before Contenu placé avant le champ.
     * @var string $name Clé d'indice de la valeur de soumission du champ.
     * @var string $value Valeur courante de soumission du champ.
     * @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * @var array $options Liste des options du contrôleur ajax.
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
            'attrs.data-control' => 'datepicker',
            'attrs.data-options' => $this->get('options', []),
        ]);

        return $this;
    }
}