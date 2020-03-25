<?php
/**
 * Bouton de formulaire.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var tiFy\Contracts\Form\ButtonController $button
 */
?>
<?php if ($button->hasWrapper()) : $this->layout('wrapper-button', $this->all()); endif; ?>

<?php echo $button->get('before'); ?>
<?php echo $button; ?>
<?php echo $button->get('after'); ?>