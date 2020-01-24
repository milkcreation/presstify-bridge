<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>
<?php if ($this->get('content.header', '') !== false): ?>
    <div data-control="modal.content.header"><?php echo $this->get('content.header', ''); ?></div>
<?php endif; ?>
<?php if ($this->get('content.body', '') !== false): ?>
    <div data-control="modal.content.body"><?php echo $this->get('content.body', ''); ?></div>
<?php endif; ?>
<?php if ($this->get('content.footer', '') !== false): ?>
    <div data-control="modal.content.footer"><?php echo $this->get('content.footer', ''); ?></div>
<?php endif; ?>