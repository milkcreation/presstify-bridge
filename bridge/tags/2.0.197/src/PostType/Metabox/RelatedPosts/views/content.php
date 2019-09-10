<?php
/**
 * @var tiFy\View\ViewController $this
 * @var tiFy\Wordpress\Contracts\QueryPost $item
 */
?>
<div <?php echo $this->htmlAttrs($this->get('attrs', [])); ?>>
    <div class="MetaboxRelatedPosts-suggest">
        <?php echo field('suggest', $this->get('suggest', [])); ?>
    </div>
    <ul id="MetaboxRelatedPosts-items--<?php echo $this->get('index', 0); ?>"
        class="MetaboxRelatedPosts-items"
        data-control="metabox.related-posts.items"
    >
        <?php foreach ($this->get('items', []) as $index => $item) : ?>
           <?php $this->insert('item', compact('index', 'item', 'name')); ?>
        <?php endforeach; ?>
    </ul>
</div>