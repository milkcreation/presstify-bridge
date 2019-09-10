<?php declare(strict_types=1);

namespace tiFy\Field\Fields\SelectImage;

use tiFy\Contracts\Field\{FieldFactory as FieldFactoryContract, SelectImage as SelectImageContract};
use tiFy\Contracts\Field\SelectChoice;
use tiFy\Field\FieldFactory;

class SelectImage extends FieldFactory implements SelectImageContract
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
     *      @var string|string[]|array|SelectChoice[]|SelectImageChoices $choices Chemin absolu vers les éléments de la
     *                                                                            liste de selection|Liste de selection
     *                                                                            d'éléments.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'   => [],
            'after'   => '',
            'before'  => '',
            'name'    => '',
            'value'   => null,
            'viewer'  => [],
            'choices' => []
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldFactoryContract
    {
        parent::parse();

        $this->set('attrs.class', trim($this->get('attrs.class', '%s') . ' FieldSelectJs FieldSelectImage'));

        $choices = $this->get('choices', []);
        if (!$choices instanceof SelectImageChoices) {
            $choices = new SelectImageChoices($choices, $this->getValue());
        }
        $this->set('choices', $choices->setField($this));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseDefaults(): FieldFactoryContract
    {
        $this->parseViewer();

        return $this;
    }
}