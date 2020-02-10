<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Slider;

use tiFy\Contracts\Partial\{PartialDriver as PartialDriverContract, Slider as SliderContract};
use tiFy\Partial\PartialDriver;
use tiFy\Validation\Validator as v;

class Slider extends PartialDriver implements SliderContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     * @var array $attrs Attributs HTML du champ.
     * @var string $after Contenu placé après le champ.
     * @var string $before Contenu placé avant le champ.
     * @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * @var string[]|callable[] $items Liste des éléments. Liste de sources d'image|Liste de contenu HTML|Liste de
     *                                      fonctions. défaut : @see https://picsum.photos/images
     * @var array $options Liste des attributs de configuration du pilote d'affichage.
     * @see http://kenwheeler.github.io/slick/#settings
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'   => [],
            'after'   => '',
            'before'  => '',
            'viewer'  => [],
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
    public function parse(): PartialDriverContract
    {
        parent::parse();

        $items = $this->get('items', []);
        foreach ($items as &$item) {
            if (is_callable($item)) {
                $item = call_user_func($item);
            } elseif (is_array($item)) {
            } elseif (v::url()->validate($item)) {
                $item = "<img src=\"{$item}\" alt=\"\"/>";
            }
        }
        $this->set([
            'items'              => $items,
            'attrs.data-control' => 'slider',
            'attrs.data-slick'   => htmlentities(json_encode($this->get('options', []))),
        ]);

        return $this;
    }
}