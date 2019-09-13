<?php
/**
 * @var tiFy\View\ViewController $this
 * @var tiFy\Wordpress\Contracts\QueryPost $item
 */
?>
<?php if ($thumbnail = $item->getThumbnail('thumbnail', ['class' => 'MetaboxRelatedPosts-itemThumbImg'])) : ?>
    <figure class="MetaboxRelatedPosts-itemThumb">
        <?php echo $thumbnail; ?>
    </figure>
<?php else : ?>
    <figure class="MetaboxRelatedPosts-itemThumb">
        <?php echo partial('holder', [
            'content' => __('indispo.', 'tify'),
        ]); ?>
    </figure>
<?php endif; ?>

<h4 class="MetaboxRelatedPosts-itemTitle"><?php echo $item->getTitle(); ?></h4>

<ul class="MetaboxRelatedPosts-itemMetas" data-control="metabox.related-posts.item.metas">
    <li class="MetaboxRelatedPosts-itemMeta MetaboxRelatedPosts-itemMeta--post_type">
        <label><?php _e('Type :', 'tify'); ?></label>
        <?php echo ucfirst($item->getType()->label('singular')); ?>
    </li>
    <li class="MetaboxRelatedPosts-itemMeta MetaboxRelatedPosts-itemMeta--post_status">
        <label><?php _e('Statut :', 'tify'); ?></label>
        <?php echo $item->getStatus()->getLabel(); ?>
    </li>
</ul>