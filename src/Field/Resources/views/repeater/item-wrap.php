<?php
/**
 * @var tiFy\Field\FieldView $this
 */
?>
<li data-control="repeater.item">
    <div data-control="repeater.item.content">
        <?php $this->insert('item', [
            'index' => $this->get('index'),
            'name'  => $this->getName(),
            'value' => $this->get('value'),
            'args'  => $this->get('args', []),
        ]); ?>
    </div>
</li>