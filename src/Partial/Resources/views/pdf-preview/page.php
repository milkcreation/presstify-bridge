<?php
/**
 * PdfPreview > Compte des pages.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\PartialView $this
 */
?>
<span <?php echo $this->htmlAttrs($this->get('page.attrs', []));?>>
    <?php $this->insert('page-num', $this->all()); ?>/<?php $this->insert('page-total', $this->all()); ?>
</span>