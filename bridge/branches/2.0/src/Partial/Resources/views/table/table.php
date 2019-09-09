<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>
<?php $this->before(); ?>
<div class="Table">
    <?php if ($this->get('header')) $this->insert('header', $this->all()); ?>

    <?php $this->insert('body', $this->all()); ?>

    <?php if ($this->get('footer')) $this->insert('footer', $this->all()); ?>
</div>
<?php $this->after();