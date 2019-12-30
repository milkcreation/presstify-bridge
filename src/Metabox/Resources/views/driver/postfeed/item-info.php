<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 * @var tiFy\Wordpress\Contracts\Query\QueryPost $item
 */
?>
<div class="MetaboxPostfeed-itemInfoLine MetaboxPostfeed-itemInfoLine--post_type">
    <label><?php _e('Type :', 'tify'); ?></label>
    <?php echo ucfirst($item->getType()->label('singular_name')); ?>
</div>
<div class="MetaboxPostfeed-itemInfoLine MetaboxPostfeed-itemInfoLine--post_status">
    <label><?php _e('Statut :', 'tify'); ?></label>
    <?php echo $item->getStatus()->getLabel(); ?>
</div>