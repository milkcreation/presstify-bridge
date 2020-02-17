<?php
/**
 * Corps de la table.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\View $this
 */
?>
<tbody id="the-list"<?php echo ($singular = $this->param('singular', '')) ? " data-wp-lists=\"list:{$singular}\"" : ''; ?>>
    <?php if ($this->items()->exists()) : ?>
        <?php foreach($this->items() as $item) : ?>
            <?php $this->insert('tbody-row', ['item' => $item]); ?>
        <?php endforeach; ?>
    <?php else : ?>
        <tr class="no-items">
            <td class="colspanchange" colspan="<?php echo $this->columns()->countVisible(); ?>">
                <?php echo $this->label('no_item'); ?>
            </td>
        </tr>
    <?php endif; ?>
</tbody>