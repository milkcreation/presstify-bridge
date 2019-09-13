<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>

<div class="Table-foot">
    <div class="Table-footTr Table-tr">
    <?php foreach ($this->get('columns', [])  as $name => $label) : ?>
        <div class="Table-cell<?php echo $this->get('count'); ?> Table-footTh Table-footTh--<?php echo $name; ?> Table-th Table-th--<?php echo $name; ?>">
            <?php echo $label; ?>
        </div>
    <?php endforeach; ?>
    </div>
</div>

