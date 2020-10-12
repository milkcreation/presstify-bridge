<?php
/**
 * Structure du formulaire.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 */
?>
<?php echo $this->before(); ?>
<?php echo partial('tag', array_merge($this->form()->get('container'), [
    'content' => $this->fetch('form', $this->all()),
])); ?>
<?php echo $this->after();