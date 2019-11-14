<?php
/**
 * Tab
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\Partials\Tab\TabView $this
 * @var tiFy\Contracts\Partial\TabItem[] $items
 * @var tiFy\Contracts\Partial\TabItem $item
 */
?>
<?php $this->before(); ?>
<div <?php $this->attrs(); ?>>
    <?php $this->insert('nav', ['depth' => 0, 'items' => $this->get('items', [])]); ?>
    <?php $this->insert('content', ['depth' => 0, 'items' => $this->get('items', [])]); ?>
</div>
<?php $this->after();