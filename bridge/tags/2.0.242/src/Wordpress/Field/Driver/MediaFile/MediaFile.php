<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Driver\MediaFile;

use tiFy\Contracts\Field\FieldDriver as BaseFieldDriverContract;
use tiFy\Wordpress\Contracts\Field\MediaFile as MediaFileContract;
use tiFy\Wordpress\Field\FieldDriver;

class MediaFile extends FieldDriver implements MediaFileContract
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
     * @var string filetype Type de fichier permis ou MimeType. ex. image|image/png|video|video/mp4|application/pdf
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'    => [],
            'before'   => '',
            'after'    => '',
            'name'     => '',
            'value'    => '',
            'viewer'   => [],
            'options'  => [],
            'filetype' => '',
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): BaseFieldDriverContract
    {
        parent::parse();

        $defaultClasses = [
            'addnew' => 'FieldMediaFile-addnew',
            'input'  => 'FieldMediaFile-input',
            'reset'  => 'FieldMediaFile-reset ThemeButton--close',
            'wrap'   => 'FieldMediaFile-wrap ThemeInput--media',
        ];
        foreach ($defaultClasses as $k => $v) {
            $this->set(["classes.{$k}" => sprintf($this->get("classes.{$k}", '%s'), $v)]);
        }

        $media_id = $this->get('value', 0);
        if ( ! $filename = get_attached_file($media_id)) {
            $media_id = 0;
        }

        $this->set([
            'attrs'                                   => array_merge(
                ['placeholder' => __('Cliquez pour ajouter un fichier', 'tify')],
                $this->get('attrs', []),
                [
                    'autocomplete' => 'off',
                    'data-control' => 'media-file',
                    'data-value'   => $media_id ? get_the_title($media_id) . ' &rarr; ' . basename($filename) : '',
                    'disabled',
                ]
            ),
            'attrs.data-options.classes'              => $this->get('classes', []),
            'attrs.data-options.library'              => array_merge($this->get('options', []), [
                'editing'  => true,
                'multiple' => false,
            ]),
            'attrs.data-options.library.library.type' => $this->get('filetype'),
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseDefaults(): BaseFieldDriverContract
    {
        $default_class = 'FieldMediaFile FieldMediaFile' . '--' . $this->getIndex();
        if (!$this->has('attrs.class')) {
            $this->set('attrs.class', $default_class);
        } else {
            $this->set('attrs.class', sprintf($this->get('attrs.class', ''), $default_class));
        }

        $this->parseName();
        $this->parseValue();
        $this->parseViewer();

        return $this;
    }
}
