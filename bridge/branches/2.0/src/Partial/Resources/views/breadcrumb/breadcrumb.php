<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>
<?php $this->before(); ?>
<ol <?php $this->attrs(); ?>>
    <?php foreach ($this->get('items', []) as $item) : echo $item; endforeach;?>
</ol>
<?php $this->after();