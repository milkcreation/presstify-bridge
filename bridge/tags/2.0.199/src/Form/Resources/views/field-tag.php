<?php
/**
 * Marqueur de champ de formulaire requis.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var tiFy\Contracts\Form\FactoryField $field
 */
?>
<?php if ($tagged = $field->get('required.tagged')) : ?>
    <?php echo partial('tag', $tagged); ?>
<?php endif; ?>