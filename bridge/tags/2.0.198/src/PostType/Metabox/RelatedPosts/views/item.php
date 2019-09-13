<?php
/**
 * @var tiFy\View\ViewController $this
 * @var int $index
 * @var tiFy\Wordpress\Contracts\QueryPost $item
 */
?>
<li id="MetaboxRelatedPosts-item--<?php echo $item->getId(); ?>"
    class="MetaboxRelatedPosts-item"
    data-control="metabox.related-posts.item"
>
    <?php $this->insert('item-content', compact('item')); ?>

    <?php echo field('hidden', [
        'attrs' => [
            'class' => 'MetaboxRelatedPosts-itemValue',
        ],
        'name'  => $this->get('name') . '[]',
        'value' => $item->getId(),
    ]); ?>

    <?php echo field('text', [
        'attrs' => [
            'autocomplete' => 'off',
            'class'        => 'MetaboxRelatedPosts-itemOrder',
            'data-control' => 'metabox.related-posts.item.order',
            'readonly',
            'size'         => '1',
        ],
        'value' => $this->get('index', 0) +1
    ]); ?>

    <?php echo partial('tag', [
        'tag'   => 'a',
        'attrs' => [
            'class'        => 'MetaboxRelatedPosts-itemMetasToggle',
            'href'         => "#MetaboxRelatedPosts-item--{$item->getId()}",
            'data-control' => 'metabox.related-posts.item.metas-toggle',
        ],
    ]); ?>

    <?php echo partial('tag', [
        'tag'     => 'span',
        'attrs'   => [
            'class'        => 'MetaboxRelatedPosts-itemSort',
            'href'         => "#MetaboxRelatedPosts-item--{$item->getId()}",
            'data-control' => 'metabox.related-posts.item.sort',
        ],
        'content' => '...',
    ]); ?>

    <?php echo partial('tag', [
        'tag'   => 'a',
        'attrs' => [
            'class'        => 'MetaboxRelatedPosts-itemButtonRemove ThemeButton--remove',
            'href'         => "#MetaboxRelatedPosts-item--{$item->getId()}",
            'data-control' => 'metabox.related-posts.item.remove',
        ],
    ]); ?>
</li>