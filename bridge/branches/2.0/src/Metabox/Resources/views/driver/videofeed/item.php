<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 */
?>
<div class="MetaboxVideofeed-itemPoster">
    <?php echo field('media-image', [
        'attrs'   => [
            'data-control' => 'metabox-videofeed.item.poster',
        ],
        'content' => __('Image de couverture', 'tify'),
        'infos'   => false,
        'height'  => 150,
        'name'    => $this->get('name') . '[poster]',
        'value'   => $this->get('value.poster'),
        'width'   => 150,
    ]); ?>
</div>

<div class="MetaboxVideofeed-itemSrc">
    <?php echo field('textarea', [
        'attrs' => [
            'data-control' => 'metabox-videofeed.item.input',
            'placeholder'  => __('Url de la vidéo ou iframe', 'tify'),
            'rows'         => 5,
            'cols'         => 40,
        ],
        'name'  => $this->get('name') . '[src]',
        'value' => $this->get('value.src'),
    ]); ?>
</div>

<div class="MetaboxVideofeed-itemLibrary">
    <?php echo partial('tag', [
        'attrs'   => [
            'data-control' => 'metabox-videofeed.item.library',
        ],
        'content' => __('Vidéo de la librairie média', 'tify'),
        'tag'     => 'button',
    ]); ?>
</div>