<?php
/**
 * Champ de formulaire.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var tiFy\Contracts\Form\FactoryField $field
 */
?>
<?php if ($field->hasWrapper()) : $this->layout('wrapper-field', $this->all()); endif; ?>

<?php echo $field->before(); ?>
<?php $this->insert('field-content', compact('field')); ?>
<?php echo $field->after();