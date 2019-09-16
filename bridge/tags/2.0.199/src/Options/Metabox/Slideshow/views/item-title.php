<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 */
?>

<div class="MetaboxOptions-slideshowListItemInput MetaboxOptions-slideshowListItemInput--url">
    <h3><?php _e('Titre', 'tify'); ?></h3>

    <?php
    echo field(
        'text',
        [
            'name'  => "{$this->get('name')}[title]",
            'value' => $this->get('title')
        ]
    );
    ?>
</div>