<?php declare(strict_types=1);

namespace tiFy\Field\Driver\ToggleSwitch;

use tiFy\Contracts\Field\FieldDriver as FieldDriverContract;
use tiFy\Contracts\Field\ToggleSwitch as ToggleSwitchContract;
use tiFy\Field\FieldDriver;

class ToggleSwitch extends FieldDriver implements ToggleSwitchContract
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
     *      @var string $label_on
     *      @var string $label_off
     *      @var bool|int|string $value_on
     *      @var bool|int|string $value_off
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'     => [],
            'after'     => '',
            'before'    => '',
            'name'      => '',
            'value'     => 'on',
            'viewer'    => [],
            'label_on'  => _x('Oui', 'FieldToggleSwitch', 'tify'),
            'label_off' => _x('Non', 'FieldToggleSwitch', 'tify'),
            'value_on'  => 'on',
            'value_off' => 'off'
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldDriverContract
    {
        parent::parse();

        $this->set('attrs.data-control', 'toggle-switch');

        return $this;
    }
}