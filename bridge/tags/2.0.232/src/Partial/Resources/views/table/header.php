<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>
<div class="Table-head">
    <div class="Table-headTr Table-tr">
    <?php foreach ($this->get('columns', []) as $name => $label) : ?>
        <div class="Table-cell<?php echo $this->get('count'); ?> Table-headTh Table-headTh--<?php echo $name; ?> Table-th Table-th--<?php echo $name; ?>">
            <?php echo $label;?>
        </div>
    <?php endforeach; ?>
    </div>
</div>