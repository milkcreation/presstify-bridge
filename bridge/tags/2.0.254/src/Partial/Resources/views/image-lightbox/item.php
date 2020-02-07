<?php
/**
 * @var tiFy\Partial\PartialView $this
 * @var tiFy\Contracts\Partial\ImageLightboxItem $item
 */
?>
<a <?php echo $item->getAttrs(); ?>>
    <?php echo $item->getContent(); ?>
</a>