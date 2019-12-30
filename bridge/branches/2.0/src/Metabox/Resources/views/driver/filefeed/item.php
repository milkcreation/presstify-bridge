<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 */
?>
<span class="MetaboxFilefeed-itemIcon">
    <?php echo $this->get('icon'); ?>
</span>

<span class="MetaboxFilefeed-itemTitle">
    <?php echo $this->get('title'); ?>
</span>

<span class="MetaboxFilefeed-itemMime">
    <?php echo $this->get('mime'); ?>
</span>

<?php echo field('hidden', [
    'attrs' => [
        'data-control' => 'metabox-filefeed.item.input'
    ],
    'name'  => $this->get('name'),
    'value' => $this->get('value')
]);