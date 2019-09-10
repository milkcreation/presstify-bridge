<?php

namespace tiFy\PostType\Metabox\ImageGallery;

use tiFy\Metabox\MetaboxWpPostController;

class ImageGallery extends MetaboxWpPostController
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        add_action(
            'wp_ajax_tify_tab_metabox_post_type_image_gallery_add_item',
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
        $this->set('items', get_post_meta($post->ID, $this->get('name'), true) ? : []);

        return $this->viewer('content', $this->all());
    }

    /**
     * {@inheritdoc}
     */
    public function defaults()
    {
        return [
            'name' => '_tify_taboox_image_gallery',
            'max'  => -1
        ];
    }

    /**
     * Affichage d'un élément
     *
     * @param int $id Identifiant de qualification de l'élément.
     * @param int $order Ordre d'affichage de l'élément.
     * @param string string $name Nom d'enregistrement de l'élément.
     *
     * @return string
     */
    public function displayItem($id, $order, $name)
    {
        // Bypass
        if (!$image = wp_get_attachment_image_src($id, 'thumbnail')) :
            return '';
        endif;

        $item['id'] = $id;
        $item['src'] = $image[0];
        $item['name'] = "{$name}[]";
        $item['order'] = $order;

        return $this->viewer('item', $item);
    }


    /**
     * {@inheritdoc}
     */
    public function header($post = null, $args = null, $null = null)
    {
        return $this->item->getTitle() ? : __('Galerie d\'images', 'tify');
    }

    /**
     * {@inheritdoc}
     */
    public function load($current_screen)
    {
        add_action('admin_enqueue_scripts', function () {
            @wp_enqueue_media();

            wp_enqueue_style(
                'MetaboxPostTypeImageGallery',
                asset()->url('post-type/metabox/image-gallery/css/styles.css'),
                ['tiFyAdmin'],
                180808
            );

            wp_enqueue_script(
                'MetaboxPostTypeImageGallery',
                asset()->url('post-type/metabox/image-gallery/js/scripts.js'),
                ['jquery', 'jquery-ui-sortable'],
                180808,
                true
            );
            wp_localize_script(
                'MetaboxPostTypeImageGallery',
                'tify_taboox_image_gallery',
                [
                    'maxAttempt' => __('Nombre maximum d\'images dans la galerie atteint', 'tify'),
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
            $this->get('name') => true
        ];
    }

    /**
     * Action Ajax.
     *
     * @return string
     */
    public function wp_ajax()
    {
        echo $this->displayItem(
            request()->post('id'),
            request()->post('order'),
            request()->post('name')
        );

        exit;
    }
}