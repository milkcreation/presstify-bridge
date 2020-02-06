<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\ImageLightbox;

use tiFy\Contracts\Partial\{
    ImageLightbox as ImageLightboxContract,
    ImageLightboxItem as ImageLightboxItemContract,
    PartialDriver as PartialDriverContract};
use tiFy\Partial\PartialDriver;
use tiFy\Validation\Validator as v;

/**
 * @see https://github.com/marekdedic/imagelightbox
 * @see http://marekdedic.github.io/imagelightbox/
 */
class ImageLightbox extends PartialDriver implements ImageLightboxContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var string|null $group Groupe d'affectation de la liste des éléments.
     *      @var string|array|ImageLightboxItemContract[] $items Liste des éléments.
     *      @var array $options Liste des options {
     *          @see https://github.com/marekdedic/imagelightbox
     *
     *          @var string $selector Par défaut 'a[data-imagelightbox]'.
     *          @var string $id Par défaut 'imagelightbox'.
     *          @var string $allowedTypes Type de fichier permis. ex. 'png|jpg|jpeg|gif' Par défaut tous.
     *          @var int $animationSpeed Vitesse d'animation. Par défaut 250.
     *          @var boolean $activity Affichage de l'indicateur d'activité. Par défaut false.
     *          @var boolean $arrows Affichage des flèches de navigation suivant/précédent. Par défaut false.
     *          @var boolean $button Affichage du bouton de fermeture. Par défaut false.
     *          @var boolean $caption Affichage des légendes. Par défaut false.
     *          @var boolean $enableKeyboard Activation des raccourcis clavier (flèches d/g + echap). Par défaut true.
     *          @var boolean $history ??? enable image permalinks and history Par défaut false.
     *          @var boolean $fullscreen Activation du mode plein écran. Par défaut false.
     *          @var int $gutter Window height less height of image as a percentage. Par défaut 10.
     *          @var int $offsetY Vertical offset in terms of gutter. Par défaut 0.
     *          @var boolean $navigation Affichage de la navigation. Par défaut false.
     *          @var boolean $overlay Affichage du fond de recouvrement. Par défaut false.
     *          @var boolean $preloadNext Préchargement des images en tâche de fond. Par défaut true.
     *          @var boolean $quitOnEnd Fermeture à l'issue de l'affichage de la dernière image. Par défaut false.
     *          @var boolean $quitOnImgClick Fermeture au clique sur l'image. Par défaut false.
     *          @var boolean $quitOnDocClick Fermeture au clique en dehors de l'image Par défaut true.
     *          @var boolean $quitOnEscKey Fermeture avec la touche echap. Par défaut true.
     *      }
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'         => [],
            'after'         => '',
            'before'        => '',
            'viewer'        => [],
            'group'         => null,
            'items'   => [
                'https://picsum.photos/id/768/800/800',
                'https://picsum.photos/id/669/800/800',
                'https://picsum.photos/id/646/800/800',
                'https://picsum.photos/id/883/800/800',
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

        $items = [];
        foreach((array)$this->get('items', []) as &$item) {
            if ($item instanceof ImageLightboxItemContract) {
            } elseif (is_array($item)){
                $item = (new ImageLightboxItem())->set($item);
            } elseif (is_string($item) && v::url()->validate($item)) {
                $item = (new ImageLightboxItem())->set([
                    'src' => $item
                ]);
            } else {
                continue;
            }
            if (!$item->get('group')) {
                $item->set('group', $this->get('group') ? : $this->getId());
            }

            $items[] = $item->parse();

        }
        $this->set('items', $items);

        $this->set('attrs.data-control', 'image-lightbox');
        $this->set('attrs.data-options', $this->pull('options', []));

        return $this;
    }
}