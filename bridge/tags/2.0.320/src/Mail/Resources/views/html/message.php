<?php
/**
 * @var tiFy\Contracts\Mail\MailView $this
 */
?>
<?php $this->layout('html/layout', $this->all()); ?>

<?php $this->start('header'); ?>
    <?php $this->insert('html/header', $this->all()); ?>
<?php $this->end(); ?>

<?php $this->start('footer'); ?>
    <?php $this->insert('html/footer', $this->all()); ?>
<?php $this->end(); ?>

<?php $this->insert('html/body', $this->all());