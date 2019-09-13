<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>
<?php $this->before(); ?>

    <span <?php $this->attrs(); ?>>
        <button data-control="dropdown.button">
            <?php $this->insert('button', $this->all()); ?>
        </button>

        <ul data-control="dropdown.items">
            <?php foreach ($this->get('items', []) as $item) : ?>
                <li data-control="dropdown.item"><?php $this->insert('item', compact('item')); ?></li>
            <?php endforeach; ?>
        </ul>
    </span>

<?php $this->after();