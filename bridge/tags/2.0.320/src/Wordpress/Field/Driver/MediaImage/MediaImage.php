<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Driver\MediaImage;

use tiFy\Contracts\Field\FieldDriver as BaseFieldDriverContract;
use tiFy\Wordpress\Contracts\Field\MediaImage as MediaImageContract;
use tiFy\Wordpress\Field\FieldDriver;

class MediaImage extends FieldDriver implements MediaImageContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();

        add_action('admin_enqueue_scripts', function () {
            @wp_enqueue_media();
        });
    }

    /**
     * {@inheritDoc}
     *
     * @return array {
     * @var string $before Contenu placé avant le champ.
     * @var string $after Contenu placé après le champ.
     * @var string $name Clé d'indice de la valeur de soumission du champ.
     * @var string $value Valeur courante de soumission du champ.
     * @var array $attrs Attributs HTML du champ.
     * @var array $viewer Liste des attributs de configuration du controleur de gabarit d'affichage.
     * @var string $content Contenu HTML d'enrichissement de l'affichage de l'interface de saisie.
     * @var string|int $default Image par défaut. Affiché lorsqu'aucune image n'est séléctionnée.
     * @var string $format Format de l'image. cover (défaut)|contain
     * @var int $height Hauteur de l'image en pixel. 100 par defaut.
     * @var bool|string $infos Etiquette d'information complémentaires. {{largeur}} x {{hauteur}} par défaut
     * @var bool $removable Activation de la suppression de l'image active.
     * @var string|array $size Taille de l'attachment utilisé pour la prévisualisation de l'image. 'large' par défaut.
     * @var int $width Largeur de l'image en pixel. 100 par défaut.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'     => [],
            'before'    => '',
            'after'     => '',
            'name'      => '',
            'value'     => '',
            'viewer'    => [],
            'content'   => __('Cliquez sur la zone', 'tify'),
            'default'   => null,
            'format'    => 'cover',
            'height'    => 100,
            'infos'     => true,
            'removable' => true,
            'size'      => 'large',
            'width'     => 100,
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): BaseFieldDriverContract
    {
        parent::parse();

        $this->set([
            'attrs.aria-selected' => 'false',
            'attrs.style'         => "max-width:{$this->get('width')}px;max-height:{$this->get('height')}px;",
        ]);

        if ($infos = $this->get('infos')) {
            $this->set('infos', is_string($infos)
                ? $infos : sprintf(__('%dpx / %dpx', 'tify'), $this->get('width'), $this->get('height'))
            );
        } else {
            $this->set('infos', '');
        }

        if ($default = $this->get('default')) {
            if (is_numeric($default)) {
                if ($img = wp_get_attachment_image_src($default, $this->get('size'))) {
                    $this->set([
                        'preview.attrs.data-default' => $img[0],
                        'preview.attrs.style' => "background-image:url({$img[0]})",
                    ]);
                }
            } elseif (is_string($default)) {
                $this->set([
                    'preview.attrs.data-default' => $default,
                    'preview.attrs.style' => "background-image:url({$default})",
                ]);
            }
        }

        if ($value = $this->getValue()) {
            if (is_numeric($value)) {
                if ($img = wp_get_attachment_image_src($value, $this->get('size'))) {
                    $this->set([
                        'attrs.aria-selected' => 'true',
                        'preview.attrs.style' => "background-image:url({$img[0]})",
                    ]);
                }
            } elseif (is_string($value)) {
                $this->set([
                    'attrs.aria-selected' => 'true',
                    'preview.attrs.style' => "background-image:url({$value})",
                ]);
            }
        }

        $this->set([
            'attrs.data-control'         => 'media-image',
            'attrs.data-format'          => $this->get('format'),
            'preview.attrs.class'        => 'FieldMediaImage-preview',
            'preview.attrs.data-control' => 'media-image.preview',
            'sizer'                      => [
                'attrs' => [
                    'class'        => 'FieldMediaImage-sizer',
                    'data-control' => 'media-image.sizer',
                    'style'        => 'width:100%;padding-top:' .
                        (100 * ($this->get('height') / $this->get('width'))) . '%',
                ],
            ],
        ]);

        return $this;
    }
}