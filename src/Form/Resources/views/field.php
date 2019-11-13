<?php
/**
 * Champ de formulaire.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var tiFy\Contracts\Form\FactoryField $field
 */
?>
<?php echo $field->before(); ?>
<?php if ($field->hasWrapper()) : ?>
    <?php echo partial('tag', array_merge($field->get('wrapper', []), [
        'content' => $this->fetch('field-label', compact('field')) .
            $this->fetch('field-content', compact('field'))
    ])); ?>
<?php else : ?>
    <?php $this->insert('field-label', compact('field')); ?>
    <?php $this->insert('field-content', compact('field')); ?>
<?php endif; ?>
<?php echo $field->after();