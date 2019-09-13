<?php
/**
 * @var tiFy\PostType\Metabox\ImageGallery\ViewController $this
 */
?>
<div class="tiFyTabMetaboxPostTypeImageGallery">
    <ul class="tiFyTabMetaboxPostTypeImageGallery-items ThemeCards">
    <?php $order = 0; foreach ($this->get('items', []) as $attachment_id) : ?>
        <?php echo $this->displayItem($attachment_id, ++$order, $this->get('name', '')); ?>
    <?php endforeach; ?>
    </ul>

    <a href="#" class="tiFyTabMetaboxPostTypeImageGallery-add button-secondary"
       data-name="<?php echo $this->get('name', ''); ?>"
       data-max="<?php echo $this->get('max', -1); ?>"
       data-media_title="<?php _e('Galerie d\'images', 'tify'); ?>"
       data-media_button_text="<?php _e('Ajouter les images', 'tify'); ?>"
    >
        <div class="dashicons dashicons-format-gallery" style="vertical-align:middle;"></div>&nbsp;
        <?php _e('Ajouter des images', 'tify'); ?>
    </a>
</div>
