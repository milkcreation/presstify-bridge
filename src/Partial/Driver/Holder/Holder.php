<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Holder;

use tiFy\Contracts\Partial\{Holder as HolderContract, PartialDriver as PartialDriverContract};
use tiFy\Partial\PartialDriver;

class Holder extends PartialDriver implements HolderContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var string $content Contenu de remplacement.
     *      @var int $width Rapport de largeur relatif à la hauteur.
     *      @var int $height Rapport de hauteur relatif à la largeur.
     *
     * }
     */
    public function defaults() : array
    {
        return [
            'attrs'            => [],
            'after'            => '',
            'before'           => '',
            'viewer'           => [],
            'content'          => '',
            'width'            => 100,
            'height'           => 100,
        ];
    }
}