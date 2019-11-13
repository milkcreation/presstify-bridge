<?php
/**
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var tiFy\Contracts\Form\FactoryField $field
 */
?>
<table cellpadding="0" cellspacing="10" border="0" align="center" id="mailBody">
    <tbody>
    <tr>
        <td width="600" valign="top" colspan="2">
            <h3><?php echo $this->get('subject'); ?></h3>
        </td>
    </tr>
    <?php foreach ($this->get('fields', []) as $field) : ?>
        <tr>
            <?php if ($label = $field->get('mailer_label')) : ?>
                <td width="200" valign="top"><?php echo $label; ?></td>
                <td width="400" valign="top"><?php echo $field->get('mailer_value'); ?></td>
            <?php else : ?>
                <td colspan="2" width="600" valign="top"><?php echo $field->get('mailer_value'); ?></td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>