<?php
/**
 * @var tiFy\PostType\Metabox\VideoGallery\ViewController $this
 */
?>

<div class="tiFyTabMetaboxPostTypeVideoGallery">
    <ul class="tiFyTabMetaboxPostTypeVideoGallery-items">
        <?php foreach ($this->get('items', []) as $id => $attrs) : ?>
            <?php echo $this->displayItem($id, $attrs, $this->get('name')); ?>
        <?php endforeach; ?>
    </ul>

    <a
        href="#"
        class="tiFyTabMetaboxPostTypeVideoGallery-add button-secondary"
        data-name="<?php echo $this->get('name'); ?>"
        data-max="<?php echo $this->get('max'); ?>"
        data-media_title="<?php _e('Galerie de vidéos', 'tify'); ?>"
        data-media_button_text="<?php _e('Ajouter la vidéo', 'tify'); ?>"
    >
        <span class="tiFyTabMetaboxPostTypeVideoGallery-addIcon"></span>&nbsp;&nbsp;
        <?php _e('Ajouter une vidéo', 'tify'); ?>
    </a>

    <span class="spinner" style="display:inline-block;float:none;"></span>
</div>
