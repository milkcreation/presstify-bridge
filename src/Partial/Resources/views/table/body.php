<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>
<div class="Table-body">
<?php if ($datas = $this->get('datas', [])) : ?>
    <?php $num = 0; foreach ($datas as $row => $dr) : ?>
    <div class="Table-bodyTr Table-bodyTr--<?php echo $row; ?> Table-tr Table-tr--<?php echo ($num++ % 2 === 0) ? 'even' : 'odd'; ?>">
        <?php foreach ($this->get('columns', []) as $name => $label) : ?>
        <div class="Table-cell<?php echo $count; ?> Table-bodyTd Table-bodyTd--<?php echo $name; ?> Table-td">
            <span class="Table-cellLabel"><?php echo $label; ?></span>
            <?php echo $dr[$name];?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
<?php else : ?>
    <div class="Table-bodyTr Table-bodyTr--empty Table-tr">
        <div class="Table-cell1 Table-bodyTd Table-bodyTd--empty Table-td">
            <?php echo $none; ?>
        </div>
    </div>
<?php endif; ?>
</div>