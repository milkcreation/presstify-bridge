<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Slider;

use tiFy\Contracts\Partial\{PartialFactory as PartialFactoryContract, Slider as SliderContract};
use tiFy\Partial\PartialFactory;
use tiFy\Validation\Validator as v;

class Slider extends PartialFactory implements SliderContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var string[]|callable[] $items Liste des éléments. Liste de sources d'image|Liste de contenu HTML|Liste de
     *                                      fonctions. défaut : @see https://picsum.photos/images
     *      @var array $options Liste des attributs de configuration du pilote d'affichage.
     *                          @see http://kenwheeler.github.io/slick/#settings
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'         => [],
            'after'         => '',
            'before'        => '',
            'viewer'        => [],
            'items'   => [
                'https://picsum.photos/800/800/?image=768',
                'https://picsum.photos/800/800/?image=669',
                'https://picsum.photos/800/800/?image=646',
                'https://picsum.photos/800/800/?image=883',
            ],
            'options' => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialFactoryContract
    {
        parent::parse();

        $items = $this->get('items', []);
        foreach($items as &$item) {
            if (is_callable($item)) {
                $item = call_user_func($item);
            } elseif (is_array($item)){
            } elseif (v::url()->validate($item)) {
                $item = "<img src=\"{$item}\" alt=\"\"/>";
            }
        }
        $this->set('items', $items);

        $this->set('attrs.data-control', 'slider');
        $this->set('attrs.data-slick', htmlentities(json_encode($this->get('options', []))));

        return $this;
    }
}