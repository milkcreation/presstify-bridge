<?php

namespace tiFy\Options\Metabox\Slideshow;

use tiFy\Contracts\Metabox\MetaboxController as MetaboxControllerContract;
use tiFy\Metabox\MetaboxWpOptionsController;

class Slideshow extends MetaboxWpOptionsController
{
    /**
     * Liste des attributs de configuration.
     * @var array {
     * @var string $name Nom de qualification d'enregistrement.
     * @var array $attrs Liste des attributs de balisae HTML du conteneur.
     * @var string $ajax_action Action Ajax de récupération des éléments.
     * @var array $editable Liste des interfaces d'édition des vignettes actives.
     * @var integer $max Nombre maximum de vignette.
     * @var array $args Liste des attribut de requête Ajax complémentaires.
     * @todo boolean|array $suggest Liste de selection de contenu.
     * @var boolean $custom Activation de l'ajout de vignettes personnalisées.
     * @var array $options Liste des options d'affichage.
     * @var array $viewer Liste des attributs de configuration du gestionnaire de gabarit.
     * @var string $item_class Traitement de l'affichage d'un élément
     * }
     */
    protected $attributes = [
        'name'        => 'tify_taboox_slideshow',
        'attrs'       => [],
        'ajax_action' => 'metabox_options_slideshow',
        'editable'    => ['image', 'title', 'url', 'caption'],
        'max'         => - 1,
        'args'        => [],
        'custom'      => true,
        'options'     => [],
        'viewer'      => [],
        'item_class'  => SlideshowItem::class
    ];

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        add_action('wp_ajax_' . $this->get('ajax_action'), [$this, 'wp_ajax']);
    }

    /**
     * {@inheritdoc}
     */
    public function content($args = null, $null1 = null, $null2 = null)
    {
        return $this->viewer('content', $this->all());
    }

    /**
     * {@inheritdoc}
     */
    public function defaults()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function header($args = null, $null1 = null, $null2 = null)
    {
        return $this->item->getTitle() ?: __('Diaporama', 'tify');
    }

    /**
     * {@inheritdoc}
     */
    public function load($wp_screen)
    {
        add_action(
            'admin_enqueue_scripts',
            function () {
                wp_register_script(
                    'jquery.tinymce',
                    includes_url('js/tinymce') . '/tinymce.min.js',
                    [],
                    '4.9.2',
                    true
                );

                wp_register_script(
                    'tinymce',
                    '//cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.2/jquery.tinymce.min.js',
                    ['jquery', 'jquery.tinymce'],
                    true
                );

                /* @todo
                field('datetime-js')->enqueue();
                field('media-image')->enqueue();
                field('select-js')->enqueue();
                */

                wp_enqueue_style(
                    'MetaboxOptionsSlideshow',
                    asset()->url('options/metabox/slideshow/css/styles.css'),
                    [],
                    181015
                );

                wp_enqueue_script(
                    'MetaboxOptionsSlideshow',
                    asset()->url('options/metabox/slideshow/js/scripts.js'),
                    [
                        'tinymce',
                        'jquery-ui-sortable'
                    ],
                    181015,
                    true
                );

                wp_localize_script(
                    'MetaboxOptionsSlideshow',
                    'MetaboxOptionsSlideshowAdmin',
                    [
                        'l10nMax' => __('Nombre maximum de vignettes atteint', 'tify')
                    ]
                );
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function parse(): MetaboxControllerContract
    {
        parent::parse();

        $exists = array_merge(
            ['options' => [], 'items' => []],
            get_option($this->get('name')) ?: []
        );

        $items = $exists['items'] ?? [];
        array_walk(
            $items,
            function (&$attrs, $index) {
                $attrs['name']     = $this->get('name');
                $attrs['editable'] = $this->get('editable', []);
                $itemClass         = $this->get('item_class', SlideshowItem::class);

                $attrs = new $itemClass($index, $attrs, $this->viewer());
            }
        );
        $this->set('items', $items);

        $this->set(
            'options',
            array_merge(
                [
                    'ratio'       => '16:9',
                    'size'        => 'full',
                    'nav'         => true,
                    'tab'         => true,
                    'progressbar' => false
                ],
                $exists['options'] ?? []
            )
        );

        $this->set('attrs.class', 'MetaboxOptions-slideshow');

        $this->set(
            'attrs.data-options',
            array_merge(
                $this->get('args', []),
                [
                    'action'      => $this->get('ajax_action'),
                    '_ajax_nonce' => wp_create_nonce('MetaboxOptionsSlideshow'),
                    'editable'    => $this->get('editable'),
                    'name'        => $this->get('name'),
                    'max'         => $this->get('max'),
                    'viewer'      => $this->get('viewer'),
                    'item_class'  => $this->get('item_class')
                ]
            )
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function settings()
    {
        return [$this->get('name')];
    }

    /**
     * Action de récupération Ajax d'un élément.
     *
     * @return string
     */
    public function wp_ajax()
    {
        $attrs = [
            'post_id'   => request()->post('post_id'),
            'clickable' => request()->post('post_id') ? 1 : 0,
            'name'      => request()->post('name'),
            'editable'  => request()->post('editable', [])
        ];
        $itemClass = wp_unslash(request()->post('item_class', SlideshowItem::class));
        $this->viewer = null;
        $this->set('viewer', request()->post('viewer', []));

        echo new $itemClass(null, $attrs, $this->viewer());
        exit;
    }
}