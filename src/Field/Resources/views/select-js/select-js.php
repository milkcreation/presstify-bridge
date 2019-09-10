<?php
/**
 * @var tiFy\Field\FieldView $this
 * @var tiFy\Field\Fields\SelectJs\SelectJsChoices $choices
 * @var tiFy\Field\Fields\Select\SelectChoice $choice
 */
?>
<?php $this->before(); ?>

<div <?php $this->attrs(); ?>>
    <?php echo field('select', $this->get('handler', [])); ?>

    <ul class="<?php echo $this->get('classes.selection'); ?>" data-control="select-js.selection">
        <?php foreach($choices as $choice) : ?>
        <li
                class="<?php echo $this->get('classes.selectionItem'); ?>"
                data-control="select-js.selection.item"
                data-value="<?php echo $choice->getValue(); ?>"
        >
            <?php echo $choice->get('selection'); ?>
        </li>
        <?php endforeach; ?>
    </ul>

    <div class="<?php echo $this->get('classes.picker'); ?>" data-control="select-js.picker">
        <ul class="<?php echo $this->get('classes.pickerItems'); ?>" data-control="select-js.picker.items">
        <?php foreach($choices as $choice) : ?>
            <li
                    class="<?php echo $this->get('classes.pickerItem'); ?>"
                    data-control="select-js.picker.item"
                    data-value="<?php echo $choice->getValue(); ?>"
            >
                <?php echo $choice->get('picker'); ?>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php $this->after();