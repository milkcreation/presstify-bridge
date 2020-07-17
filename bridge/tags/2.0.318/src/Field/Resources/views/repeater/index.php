<?php
/**
 * @var tiFy\Field\FieldView $this
 */
?>
<?php $this->before(); ?>
<div <?php $this->attrs() ?>>
    <ul data-control="repeater.items">
        <?php foreach ($this->get('value', []) as $index => $value) : ?>
            <?php $this->insert('item-wrap', [
                'args'  => $this->get('args', []),
                'index' => $index,
                'name'  => $this->get('name'),
                'value' => $value,
            ]); ?>
        <?php endforeach; ?>
    </ul>
    <?php $this->insert('button', $this->all()); ?>
</div>
<?php $this->after();