<?php declare(strict_types=1);

namespace tiFy\Field\Fields\Button;

use tiFy\Contracts\Field\{Button as ButtonContract, FieldFactory as FieldFactoryContract};
use tiFy\Field\FieldFactory;

class Button extends FieldFactory implements ButtonContract
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
     *      @var string $content Contenu de la balise HTML.
     *      @var string $type Type de bouton. button par défaut.
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
            'content' => __('Envoyer', 'tify'),
            'type'    => 'button',
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldFactoryContract
    {
        parent::parse();

        if (!$this->has('attrs.type')) {
            $this->set('attrs.type', $this->get('type', 'button'));
        }

        return $this;
    }
}