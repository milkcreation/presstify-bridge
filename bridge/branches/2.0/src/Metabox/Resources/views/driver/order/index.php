<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 */
?>
<table class="form-table">
    <tbody>
    <tr>
        <th>
            <label><?php _e( 'Choix de l\'ordre', 'tify'); ?></label>
            <em style="display:block;color:#999;font-size:11px;font-weight:normal;">
                <?php _e( '(-1 pour masquer l\'élément)', 'tify'); ?>
            </em>
        </th>
        <td>
            <?php echo field('number', array_merge($this->params(), [
                'name' => $this->name(),
                'value' => $this->value()
            ])); ?>
        </td>
    </tr>
    </tbody>
</table>