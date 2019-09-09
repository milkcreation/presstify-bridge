<?php

namespace tiFy\PostType\Metabox\Fileshare;

use tiFy\Contracts\Metabox\MetaboxController as MetaboxControllerContract;
use tiFy\Metabox\MetaboxWpPostController;
use tiFy\Support\ParamsBag;
use tiFy\Wordpress\Proxy\Field;

class Fileshare extends MetaboxWpPostController
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        add_action(
            'wp_ajax_metabox_fileshare',
            [$this, 'wp_ajax']
        );

        add_action(
            'wp_ajax_nopriv_metabox_fileshare',
            [$this, 'wp_ajax']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function defaults()
    {
        return [
            'name'      => '_fileshare',
            'filetype'  => '', // video || application/pdf || video/flv, video/mp4,
            'max'       => - 1,
            'removable' => true,
            'sortable'  => true
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @param \WP_Post $post
     */
    public function content($post = null, $args = null, $null = null)
    {
        if ($items = get_post_meta($post->ID, $this->get('name'), true) ? : []) :
            $items = array_wrap($items);
            array_walk($items, [$this, 'itemWrap']);
        endif;
        $this->set('items', $items);

        return $this->viewer('content', $this->all());
    }

    /**
     * {@inheritdoc}
     */
    public function header($post = null, $args = null, $null = null)
    {
        return $this->item->getTitle() ? : __('Partage de fichiers', 'tify');
    }

    /**
     * Définition d'un élément.
     *
     * @param int $value Identifiant de qualification du média.
     * @param int|string $index Indice de l'élément.
     *
     * @return array
     */
    public function itemWrap(&$value, $index)
    {
        $name = $this->get('name');
        $index = !is_numeric($index) ? $index : uniqid();

        return $value = [
            'name'  => $this->get('single', false) ? "{$name}[]" : "{$name}[{$index}]",
            'value' => $value,
            'index' => $index,
            'icon'  => wp_get_attachment_image($value, [46, 60], true),
            'title' => get_the_title($value),
            'mime'  => get_post_mime_type($value)
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load($wp_screen)
    {
        add_action('admin_enqueue_scripts', function () {
            if ($this->get('max', -1) !== 1) :
                @wp_enqueue_media();

                wp_enqueue_style(
                    'MetaboxPostTypeFileshare',
                    asset()->url('post-type/metabox/fileshare/css/styles.css'),
                    ['tiFyAdmin'],
                    151216
                );

                wp_enqueue_script(
                    'MetaboxPostTypeFileshare',
                    asset()->url('post-type/metabox/fileshare/js/scripts.js'),
                    ['jquery', 'jquery-ui-sortable'],
                    151216,
                    true
                );
            else :
                Field::get('media-file')->enqueue();
            endif;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function metadatas()
    {
        return [$this->get('name')];
    }

    /**
     * @inheritDoc
     */
    public function parse(): MetaboxControllerContract
    {
        parent::parse();

        $this->set('attrs.class', sprintf($this->get('attrs.class', '%s'), 'MetaboxFileshare'));

        if ($sortable = $this->get('sortable')) :
            if (!is_array($sortable)) :
                $sortable = [];
            endif;
            $this->set('sortable', array_merge(
                    [
                        'placeholder' => 'MetaboxFileshare-itemPlaceholder',
                        'axis'        => 'y'
                    ],
                    $sortable
                )
            );
        endif;

        $this->set('attrs.data-options', [
            'ajax'      => array_merge(
                [
                    'url'    => admin_url('admin-ajax.php', 'relative'),
                    'data'   => [
                        'action'      => 'metabox_fileshare',
                        '_ajax_nonce' => wp_create_nonce('MetaboxFileshare' . $this->item->getIndex()),
                        '_id'         => $this->item->getIndex(),
                        '_viewer'     => $this->get('viewer', []),
                        'max'         => $this->get('max', - 1),
                    ],
                    'method' => 'post',
                ],
                $this->get('ajax', [])
            ),
            'wp_media'       => [
                'title' =>  __('Sélectionner les fichiers à associer', 'tify'),
                'editing' => true,
                'multiple' => true,
                'library' => [
                    'type' => $this->get('filetype')
                ]
            ],
            'name'           => $this->get('name'),
            'removable'      => $this->get('removable'),
            'sortable'       => $this->get('sortable'),
        ]);

        return $this;
    }

    /**
     * Récupération des champs via Ajax.
     *
     * @return void
     */
    public function wp_ajax()
    {
        $params = ParamsBag::createFromAttrs(request()->request->all());

        check_ajax_referer('MetaboxFileshare' . $params->get('_id'));

        if (($params->get('max') > 0) && ($params->get('index') >= $params->get('max'))) {
            wp_send_json_error(__('Nombre maximum de fichiers partagés atteint.', 'tify'));
        } else {
            $this->set('viewer', $params->get('_viewer', []));

            wp_send_json_success(
                (string)$this->viewer('item-wrap', $this->itemWrap($params->get('value'), $params->get('index')))
            );
        }
    }
}