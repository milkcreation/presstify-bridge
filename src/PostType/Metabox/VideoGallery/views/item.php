<?php
/**
 * @var tiFy\PostType\Metabox\VideoGallery\ViewController $this
 */
?>
<li class="tiFyTabMetaboxPostTypeVideoGallery-item">
    <div class="tiFyTabMetaboxPostTypeVideoGallery-itemPoster">
        <?php
        echo partial(
            'tag',
            [
                'tag'     => 'a',
                'attrs'   => [
                    'href'                   => '#',
                    'data-media_title'       => __('Sélectionner une jaquette', 'tify'),
                    'data-media_button_text' => __('Ajouter la jaquette', 'tify'),
                    'style'                  => "background-image:url({$poster_src});",
                    'class'                  => 'tiFyTabMetaboxPostTypeVideoGallery-itemPosterAdd',
                ],
                'content' => __('Changer la jaquette', 'tify'),
            ]
        );
        ?>
        <?php
        echo field(
            'hidden',
            [
                'name'  => "{$name}[{$id}][poster]",
                'value' => $this->get('poster'),
            ]
        )
        ?>
    </div>

    <div class="tiFyTabMetaboxPostTypeVideoGallery-itemSrc">
        <?php
        echo field(
            'textarea',
            [
                'name'  => "{$name}[{$id}][src]",
                'attrs' => [
                    'placeholder' => __('Vidéo de la galerie ou iframe', 'tify'),
                    'rows'        => 5,
                    'cols'        => 40,
                ],
                'value' => $this->get('src', ''),
            ]
        );
        ?>

        <?php
        echo partial(
            'tag',
            [
                'tag'     => 'a',
                'attrs'   => [
                    'href'                   => '#',
                    'class'                  => 'tiFyTabMetaboxPostTypeVideoGallery-itemSrcAdd dashicons dashicons-admin-media',
                    'data-media_title'       => __('Sélectionner une vidéo', 'tify'),
                    'data-media_button_text' => __('Ajouter la vidéo', 'tify'),
                ],
                'content' => '',
            ]
        );
        ?>
    </div>

    <a href="#remove" class="tiFyTabMetaboxPostTypeVideoGallery-itemRemove ThemeButton--remove"></a>
</li>
