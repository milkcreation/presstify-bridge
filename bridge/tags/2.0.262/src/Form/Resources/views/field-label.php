<?php
/**
 * Etiquette de champ de formulaire.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var tiFy\Contracts\Form\FactoryField $field
 */
?>
<?php if ($field->hasLabel()) : ?>
    <?php if ($field->get('label.wrapper')) : $this->layout('wrapper-label', $this->all()); endif; ?>
    <?php echo partial('tag', $field->get('label', [])); ?>
    <?php $this->insert('field-tag', compact('field')); ?>
<?php endif;