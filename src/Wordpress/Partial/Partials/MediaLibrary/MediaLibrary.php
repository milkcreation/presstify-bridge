<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Partials\MediaLibrary;

use tiFy\Contracts\Partial\PartialFactory as BasePartialFactoryContract;
use tiFy\Wordpress\Contracts\Partial\{MediaLibrary as MediaLibraryContract, PartialFactory as PartialFactoryContract};
use tiFy\Wordpress\Partial\PartialFactory;

class MediaLibrary extends PartialFactory implements MediaLibraryContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('admin_enqueue_scripts', function () {
            @wp_enqueue_media();
        });
    }

    /**
     * {@inheritDoc}
     *
     * @return array {
     * @var array $attrs Attributs HTML du champ.
     * @var string $after Contenu placé après le champ.
     * @var string $before Contenu placé avant le champ.
     * @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'   => [],
            'after'   => '',
            'before'  => '',
            'viewer'  => [],
            'button'  => [
                'tag'     => 'button',
                'content' => __('Ajouter un média', 'tify'),
            ],
            'options' => [
                'title'    => __('Sélectionner les fichiers à associer', 'tify'),
                'editing'  => true,
                'multiple' => true,
                'library'  => [],
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): PartialFactoryContract
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parse(): BasePartialFactoryContract
    {
        parent::parse();

        $this->set([
            'attrs.data-control'        => 'media-library',
            'attrs.data-options'        => $this->pull('options', []),
            'button.attrs.data-control' => 'media-library.open',
        ]);

        return $this;
    }
}