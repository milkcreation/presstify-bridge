<?php
/**
 * @var tiFy\PostType\Metabox\ImageGallery\ViewController $this
 */
?>

<li class="tiFyTabMetaboxPostTypeImageGallery-item ThemeCard">
    <div class="CardContainer">
        <img src="<?php echo $this->get('src', ''); ?>" class="tiFyTabMetaboxPostTypeImageGallery-itemThumbnail CardImg" />
        <input type="hidden" name="<?php echo $this->get('name', ''); ?>" value="<?php echo $this->get('id', 0); ?>" />
        <a href="#remove" class="tiFyTabMetaboxPostTypeImageGallery-itemRemove ThemeButton--remove"></a>
        <input type="text" class="tiFyTabMetaboxPostTypeImageGallery-itemOrder CardOrder" value="<?php echo $this->get('order', 0); ?>" size="1" readonly />
    </div>
</li>