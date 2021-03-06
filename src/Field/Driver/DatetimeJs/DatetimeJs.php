<?php declare(strict_types=1);

namespace tiFy\Field\Driver\DatetimeJs;

use Exception;
use tiFy\Contracts\Field\{DatetimeJs as DatetimeJsContract, FieldDriver as FieldDriverContract};
use tiFy\Field\FieldDriver;
use tiFy\Support\DateTime;

class DatetimeJs extends FieldDriver implements DatetimeJsContract
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
     *      @var string $format Format d'enregistrement de la valeur 'datetime': Y-m-d H:i:s|'date': Y-m-d|'time': H:i:s.
     *      @var bool $none_allowed Activation de permission d'utilisation de valeur de nulle liée au format de la valeur (ex: datetime 0000-00-00 00:00:00).
     *      @var array $fields {
     *          Liste des champs de saisie.
     *          Tableau indexés des champs de saisie (day|month|year|hour|minute|second) ou tableau associatif des attributs de champs.
     *          @see \tiFy\Field\Driver\Number\Number
     *          @see \tiFy\Field\Driver\Select\Select
     *      }
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
            'format'       => 'datetime',
            'none_allowed' => false,
            'fields'       => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldDriverContract
    {
        parent::parse();

        if (!$this->get('fields', [])) {
            switch ($this->get('format')) {
                default :
                case 'datetime' :
                    $this->set('fields', ['year', 'month', 'day', 'hour', 'minute', 'second']);
                    break;
                case 'time' :
                case 'date' :
                    $this->set('fields', ['year', 'month', 'day']);
                    break;
            }
        }

        $this->set('attrs.data-control', 'datetime-js');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        try {
            $date = new DateTime($this->getValue());
        } catch(Exception $e) {
            try {
                $date = DateTime::createFromIsoFormat('DD/MM/YYYY', $this->getValue());
            } catch(Exception $e) {
                return $e->getMessage();
            }
        }

        $Y = $date->format('Y');
        $m = $date->format('m');
        $d = $date->format('d');
        $H = $date->format('H');
        $i = $date->format('i');
        $s = $date->format('s');

        switch ($this->get('format')) {
            default :
            case 'datetime' :
                $value = "{$Y}-{$m}-{$d} {$H}:{$i}:{$s}";
                break;
            case 'date' :
                $value = "{$Y}-{$m}-{$d}";
                break;
            case 'time' :
                $value = "{$H}:{$i}:{$s}";
                break;
        }

        // Traitement des arguments des champs de saisie
        $year = '';
        $month = '';
        $day = '';
        $hour = '';
        $minute = '';
        $second = '';

        foreach ($this->get('fields') as $field_name => $field_attrs) {
            if (is_int($field_name)) {
                $field_name = (string)$field_attrs;
                $field_attrs = [];
            }

            switch ($field_name) {
                case 'year' :
                    $field_attrs = array_merge(
                        [
                            'attrs' => [
                                'id'           => $this->getId() . "-handler-yyyy",
                                'class'        => 'FieldDatetimeJs-field FieldDatetimeJs-field--year',
                                'size'         => 4,
                                'maxlength'    => 4,
                                'min'          => 0,
                                'autocomplete' => 'off'
                            ],
                            'value' => zeroise($Y, 4)
                        ],
                        $field_attrs
                    );

                    $year = field('number', $field_attrs);
                    break;

                case 'month' :
                    global $wp_locale;

                    $choices = [];
                    if ($this->get('none_allowed')) {
                        $choices[0] = __('Aucun', 'tify');
                    }
                    for ($n = 1; $n <= 12; $n++) {
                        $choices[zeroise($n, 2)] = $wp_locale->get_month_abbrev($wp_locale->get_month($n));
                    }

                    $field_attrs = array_merge(
                        [
                            'attrs'   => [
                                'id'           => $this->getId() . "-handler-mm",
                                'class'        => 'FieldDatetimeJs-field FieldDatetimeJs-field--month',
                                'autocomplete' => 'off'
                            ],
                            'choices' => $choices,
                            'value'   => zeroise($m, 2)
                        ],
                        $field_attrs
                    );

                    $month = field('select', $field_attrs);
                    break;

                case 'day' :
                    $field_attrs = array_merge(
                        [
                            'attrs' => [
                                'id'           => $this->getId() . "-handler-dd",
                                'class'        => 'FieldDatetimeJs-field FieldDatetimeJs-field--day',
                                'size'         => 2,
                                'maxlength'    => 2,
                                'min'          => $this->get('none_allowed') ? 0 : 1,
                                'max'          => 31,
                                'autocomplete' => 'off',
                            ],
                            'value' => zeroise($d, 2)
                        ],
                        $field_attrs
                    );

                    $day = field('number', $field_attrs);
                    break;

                case 'hour' :
                    $field_attrs = array_merge(
                        [
                            'attrs' => [
                                'id'           => $this->getId() . "-handler-hh",
                                'class'        => 'FieldDatetimeJs-field FieldDatetimeJs-field--hour',
                                'size'         => 2,
                                'maxlength'    => 2,
                                'min'          => 0,
                                'max'          => 23,
                                'autocomplete' => 'off'
                            ],
                            'value' => zeroise($H, 2)
                        ],
                        $field_attrs
                    );

                    $hour = field('number', $field_attrs);
                    break;

                case 'minute' :
                    $field_attrs = array_merge(
                        [
                            'attrs' => [
                                'id'           => $this->getId() . "-handler-ii",
                                'class'        => 'FieldDatetimeJs-field FieldDatetimeJs-field--minute',
                                'size'         => 2,
                                'maxlength'    => 2,
                                'min'          => 0,
                                'max'          => 59,
                                'autocomplete' => 'off'
                            ],
                            'value' => zeroise($i, 2)
                        ],
                        $field_attrs
                    );

                    $minute = field('number', $field_attrs);
                    break;

                case 'second' :
                    $field_attrs = array_merge(
                        [
                            'attrs' => [
                                'id'           => $this->getId() . "-handler-ss",
                                'class'        => 'FieldDatetimeJs-field FieldDatetimeJs-field--second',
                                'size'         => 2,
                                'maxlength'    => 2,
                                'min'          => 0,
                                'max'          => 59,
                                'autocomplete' => 'off'
                            ],
                            'value' => zeroise($s, 2)
                        ],
                        $field_attrs
                    );

                    $second = field('number', $field_attrs);
                    break;
            }
        }

        ob_start();
        ?><?php $this->before(); ?>
        <div <?php $this->attrs(); ?>>
            <?php printf('%3$s %2$s %1$s %4$s %5$s %6$s', $year, $month, $day, $hour, $minute, $second); ?>

            <?php
            echo field(
                'hidden',
                [
                    'attrs' => [
                        'id'           => 'FieldDatetimeJs-input--' . $this->getIndex(),
                        'class'        => 'FieldDatetimeJs-field FieldDatetimeJs-field--value',
                        'name'         => $this->getName(),
                        'value'        => $value
                    ]
                ]
            );
            ?>
        </div>
        <?php $this->after(); ?><?php
        return ob_get_clean();
    }
}