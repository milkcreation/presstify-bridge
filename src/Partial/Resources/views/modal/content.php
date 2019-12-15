<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>
<?php if ($header = $this->get('content.header', '')): ?>
    <div data-control="modal.content.header"><?php echo $header; ?></div>
<?php endif; ?>
<?php if ($body = $this->get('content.body', '')): ?>
    <div data-control="modal.content.body"><?php echo $body; ?></div>
<?php endif; ?>
<?php if ($footer = $this->get('content.footer', '')): ?>
    <div data-control="modal.content.footer"><?php echo $footer; ?></div>
<?php endif; ?>