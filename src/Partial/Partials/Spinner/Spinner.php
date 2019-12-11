<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Spinner;

use tiFy\Contracts\Partial\{PartialFactory as PartialFactoryContract, Spinner as SpinnerContract};
use tiFy\Partial\PartialFactory;

class Spinner extends PartialFactory implements SpinnerContract
{
    /**
     * Liste des indicateurs de pré-chargement disponibles
     * @var array
     */
    protected $spinners = [
        'rotating-plane',
        'fading-circle',
        'folding-cube',
        'double-bounce',
        'wave',
        'wandering-cubes',
        'spinner-pulse',
        'chasing-dots',
        'three-bounce',
        'circle',
        'cube-grid'
    ];

    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var string $spinner Choix de l'indicateur de préchargement. 'rotating-plane|fading-circle|folding-cube|
     *                           double-bounce|wave|wandering-cubes|spinner-pulse|chasing-dots|three-bounce|circle|
     *                           cube-grid. @see http://tobiasahlin.com/spinkit/
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'         => [],
            'after'         => '',
            'before'        => '',
            'viewer'        => [],
            'spinner' => 'spinner-pulse',
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialFactoryContract
    {
        parent::parse();

        switch($spinner = $this->get('spinner')) {
            default :
                $spinner_class = "sk-{$spinner}";
                break;
            case 'spinner-pulse':
                $spinner_class = "sk-spinner sk-{$spinner}";
                break;
        }

        $this->set('attrs.class', ($exists = $this->get('attrs.class'))
            ? "{$exists} {$spinner_class}"
            : $spinner_class
        );

        return $this;
    }
}