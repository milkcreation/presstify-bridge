<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>
<?php $this->before(); ?>
<?php echo partial('tag', $this->get('trigger', [])); ?>
<?php $this->after();