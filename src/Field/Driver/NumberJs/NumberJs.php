<?php declare(strict_types=1);

namespace tiFy\Field\Driver\NumberJs;

use tiFy\Contracts\Field\{FieldDriver as FieldDriverContract, NumberJs as NumberJsContract};
use tiFy\Field\FieldDriver;

class NumberJs extends FieldDriver implements NumberJsContract
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
     *      @var string $container Liste des attribut de configuration du conteneur de champ
     *      @var array $options {
     *          Liste des options du contrôleur ajax.
     *          @see http://api.jqueryui.com/spinner/
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
            'value'  => 0,
            'viewer' => [],
            'container' => [],
            'options'   => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldDriverContract
    {
        $this->set('container.attrs.id', 'FieldNumberJs--' . $this->getIndex());

        parent::parse();

        if ($container_class = $this->get('container.attrs.class')) {
            $this->set('container.attrs.class', "FieldNumberJs {$container_class}");
        } else {
            $this->set('container.attrs.class', 'FieldNumberJs');
        }
        $this->set('container.attrs.data-control', 'number-js');
        $this->set('container.attrs.data-options.spinner', $this->get('options', []));

        if (!$this->has('attrs.id')) {
            $this->set('attrs.id', 'FieldNumberJs-input--' . $this->getIndex());
        }
        $this->set('attrs.type', 'text');

        $this->set('attrs.data-control', 'number-js.input');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseDefaults(): FieldDriverContract
    {
        $default_class = 'FieldNumberJs-input FieldNumberJs-input' . '--' . $this->getIndex();
        if (!$this->has('attrs.class')) {
            $this->set('attrs.class', $default_class);
        } else {
            $this->set('attrs.class', sprintf($this->get('attrs.class', ''), $default_class));
        }

        $this->parseName();
        $this->parseValue();
        $this->parseViewer();

        return $this;
    }
}