<?php
/**
 * Message Au format HTML.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Mail\MailerMessageView $this
 */
?>
<?php echo $this->layout('layout', $this->all()); ?>

<div id="body_style" style="padding:15px">
    <?php echo $this->get('message'); ?>
</div>
