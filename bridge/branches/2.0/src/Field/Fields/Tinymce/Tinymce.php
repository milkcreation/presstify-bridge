<?php declare(strict_types=1);

namespace tiFy\Field\Fields\Tinymce;

use tiFy\Contracts\Field\FieldFactory as FieldFactoryContract;
use tiFy\Contracts\Field\Tinymce as TinymceContract;
use tiFy\Field\FieldFactory;

class Tinymce extends FieldFactory implements TinymceContract
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
     * @var string $content Contenu de la balise HTML.
     * @var string $type Type de bouton. button par défaut.
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
            'tag'     => 'textarea',
            'viewer'  => [],
            'options' => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldFactoryContract
    {
        parent::parse();

        $options = array_merge([
            'content_css' => [],
            'skin'        => false,
        ], $this->get('options', []));

        $this->set([
            'attrs.data-control' => 'tinymce',
            'attrs.data-options' => $options,
        ]);
        $this->pull('attrs.value');

        return $this;
    }
}