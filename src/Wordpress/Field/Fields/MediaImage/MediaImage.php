<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\MediaImage;

use tiFy\Contracts\Field\FieldFactory as BaseFieldFactoryContract;
use tiFy\Support\Str;
use tiFy\Wordpress\Contracts\Field\{FieldFactory as FieldFactoryContract, MediaImage as MediaImageContract};
use tiFy\Wordpress\Field\FieldFactory;

class MediaImage extends FieldFactory implements MediaImageContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('admin_init', function () {
            wp_register_style(
                'FieldMediaImage',
                asset()->url('field/media-image/css/styles.css'),
                [],
                180516
            );
            wp_register_script(
                'FieldMediaImage',
                asset()->url('field/media-image/js/scripts.js'),
                ['jquery'],
                180516,
                true
            );
        });

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
     * @var int $height Hauteur de l'image en pixel. 100 par defaut.
     * @var bool|string $infos Etiquette d'information complémentaires. {{largeur}} x {{hauteur}} par défaut
     * @var bool $removable Activation de la suppression de l'image active.
     * @var string|array $size Taille de l'attachment utilisé pour la prévisualisation de l'image. 'large' par défaut.
     * @var int $width Largeur de l'image en pixel. 100 par défaut.
     *  }
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
    public function display(): string
    {
        return parent::display();
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): FieldFactoryContract
    {
        wp_enqueue_style('FieldMediaImage');
        wp_enqueue_script('FieldMediaImage');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parse(): BaseFieldFactoryContract
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

        $value = $this->getValue();
        if (is_numeric($value)) {
            if ($img = wp_get_attachment_image_src($value, $this->get('size'))) {
                $this->set([
                    'attrs.aria-selected' => 'true',
                    'preview.attrs.style' => "background-image:url({$img[0]})",
                ]);
            }
        } elseif (is_string($value) && !empty($value)) {
            $this->set([
                'attrs.aria-selected' => 'true',
                'preview.attrs.style' => "background-image:url({$value})",
            ]);
        }

        $this->set([
            'attrs.data-control'         => 'media-image',
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

    /**
     * @inheritDoc
     */
    public function parseDefaults(): BaseFieldFactoryContract
    {
        $base = 'Field' . Str::studly($this->getAlias());

        $default_class = "{$base} {$base}--" . $this->getIndex();
        if (!$this->has('attrs.class')) {
            $this->set('attrs.class', $default_class);
        } else {
            $this->set('attrs.class', sprintf($this->get('attrs.class', ''), $default_class));
        }

        if (!$this->get('attrs.class')) {
            $this->forget('attrs.class');
        }

        $this->parseName();
        $this->parseValue();
        $this->parseViewer();

        return $this;
    }
}