<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\MediaFile;

use tiFy\Contracts\Field\FieldFactory as BaseFieldFactoryContract;
use tiFy\Wordpress\Contracts\Field\{FieldFactory as FieldFactoryContract, MediaFile as MediaFileContract};
use tiFy\Field\FieldFactory;

class MediaFile extends FieldFactory implements MediaFileContract
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('admin_init', function () {
            wp_register_style(
                'FieldMediaFile',
                asset()->url('field/media-file/css/styles.css'),
                ['dashicons'],
                180616
            );
            wp_register_script(
                'FieldMediaFile',
                asset()->url('field/media-file/js/scripts.js'),
                ['jquery'],
                180616,
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
     *      @var string $before Contenu placé avant le champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $name Clé d'indice de la valeur de soumission du champ.
     *      @var string $value Valeur courante de soumission du champ.
     *      @var array $attrs Attributs HTML du champ.
     *      @var array $viewer Liste des attributs de configuration du controleur de gabarit d'affichage.
     *      @var string filetype Type de fichier permis ou MimeType. ex. image|image/png|video|video/mp4|application/pdf
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
            'filetype' => '',
        ];
    }

    /**
     * @inheritDoc
     */
    public function display(): string
    {
        if (!is_admin()) {
            return '';
        }

        return parent::display();
    }

    /**
     * @inheritDoc
     */
    public function enqueue(): FieldFactoryContract
    {
        wp_enqueue_style('FieldMediaFile');
        wp_enqueue_script('FieldMediaFile');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parse(): BaseFieldFactoryContract
    {
        $this->set('viewer.directory', __DIR__ . '/Resources/views/media-file');

        parent::parse();

        $media_id = $this->get('value', 0);
        if (!$filename = get_attached_file($media_id)) {
            $media_id = 0;
        }

        $this->set('attrs.data-control', 'media_file');
        $this->set('attrs.data-options.library.type', $this->get('filetype'));
        $this->set('attrs.data-options.editing', true);
        $this->set('attrs.data-options.multiple', false);
        $this->set('attrs.aria-active', $media_id ? 'true': 'false');
        $this->set('selected_infos', $media_id ? get_the_title($media_id) . ' &rarr; ' . basename($filename) : '');

        return $this;
    }
}
