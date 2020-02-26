<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 * @var tiFy\Wordpress\Contracts\Query\QueryPost $item
 */
?>
<?php if ($thumbnail = $item->getThumbnail('thumbnail', ['class' => 'MetaboxPostfeed-itemThumbImg'])) : ?>
    <figure class="MetaboxPostfeed-itemThumb"><?php echo $thumbnail; ?></figure>
<?php else : ?>
    <figure class="MetaboxPostfeed-itemThumb">
        <?php echo partial('holder', ['content' => __('indispo.', 'tify')]); ?>
    </figure>
<?php endif; ?>

<h4 class="MetaboxPostfeed-itemTitle"><?php echo $item->getTitle(); ?></h4>

<?php echo field('hidden', [
    'attrs' => [
        'data-control' => 'metabox-postfeed.item.input',
    ],
    'name'  => $this->name() . '[]',
    'value' => $item->getId()
]);