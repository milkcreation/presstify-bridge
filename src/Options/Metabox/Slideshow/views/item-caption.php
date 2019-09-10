<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 */
?>

<div class="MetaboxOptions-slideshowListItemInput MetaboxOptions-slideshowListItemInput--caption">
    <h3><?php _e('Légende', 'tify'); ?></h3>

    <div id="<?php echo "{$this->get('name')}[caption]"; ?>" class="tinymce">
        <?php echo $this->get('caption'); ?>
    </div>
</div>