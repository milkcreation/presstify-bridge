<?php

namespace tiFy\PostType\Metabox\VideoGallery;

use tiFy\Metabox\MetaboxWpPostController;

class VideoGallery extends MetaboxWpPostController
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        add_action(
            'wp_ajax_tify_tab_metabox_post_type_video_gallery_add_item',
            [$this, 'wp_ajax']
        );

        $this->viewer()
            ->setController(ViewController::class)
            ->registerFunction('displayItem', [$this, 'displayItem']);
    }

    /**
     * {@inheritdoc}
     *
     * @param \WP_Post $post
     */
    public function content($post = null, $args = null, $null = null)
    {
        $this->set('items', post_type()->post_meta()->get($post->ID, $this->get('name')) ? : []);

        return $this->viewer('content', $this->all());
    }

    /**
     * {@inheritdoc}
     */
    public function defaults()
    {
        return [
            'name' => '_tify_taboox_video_gallery',
            'max'  => -1
        ];
    }

    /**
     * Affichage d'un élément
     *
     * @param int $id Identifiant de qualification de l'élément.
     * @param array $attrs Attributs de configuration de l'élément.
     * @param string string $name Nom d'enregistrement de l'élément.
     *
     * @return string
     */
    public function displayItem($id, $attrs, $name)
    {
        $attrs = array_merge(
            [
                'poster' => '',
                'src' => ''
            ],
            $attrs
        );
        $attrs['poster_src'] =
            ($attrs['poster'] && ($image = wp_get_attachment_image_src($attrs['poster'], 'thumbnail')))
                ? $image[0]
                : '';
        $attrs['name'] = $name;
        $attrs['id'] = $id;

        return $this->viewer('item', $attrs);
    }

    /**
     * {@inheritdoc}
     */
    public function header($post = null, $args = null, $null = null)
    {
        return $this->item->getTitle() ? : __('Galerie de vidéos', 'tify');
    }

    /**
     * {@inheritdoc}
     */
    public function load($current_screen)
    {
        add_action('admin_enqueue_scripts', function () {
            @wp_enqueue_media();

            wp_enqueue_style(
                'PostTypeMetaboxVideoGallery',
                asset()->url('post-type/metabox/video-gallery/css/styles.css'),
                ['tiFyAdmin'],
                180724
            );

            wp_enqueue_script(
                'PostTypeMetaboxVideoGallery',
                asset()->url('post-type/metabox/video-gallery/js/scripts.js'),
                ['jquery', 'jquery-ui-sortable', 'tiFyAdmin'],
                180724,
                true
            );

            wp_localize_script(
                'PostTypeMetaboxVideoGallery',
                'tify_taboox_video_gallery',
                [
                    'maxAttempt' => __('Nombre maximum de vidéos dans la galerie atteint', 'tify'),
                ]
            );
        });
    }

    /**
     * {@inheritdoc}
     */
    public function metadatas()
    {
        return [
            $this->get('name') => false
        ];
    }

    /**
     * Action Ajax.
     *
     * @return string
     */
    public function wp_ajax()
    {
        echo $this->displayItem(uniqid(), [], request()->post('name'));
        exit;
    }
}