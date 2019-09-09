<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 */
?>

<div class="MetaboxOptions-slideshowListItemInput MetaboxOptions-slideshowListItemInput--image">
    <?php
    echo field(
        'media-image',
        [
            'name'      => "{$this->get('name')}[attachment_id]",
            'value'     => $this->get('attachment_id'),
            'default'   => get_post_thumbnail_id($this->get('post_id', 0)),
            'size'      => 'thumbnail',
            'size_info' => false,
            'width'     => 150,
            'height'    => 150
        ]
    );
    ?>
</div>
