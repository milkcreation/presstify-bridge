<?php
/**
 * PdfPreview
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\PartialView $this
 */
?>
<?php echo $this->before(); ?>

<div <?php echo $this->htmlAttrs($this->get('attrs', [])); ?>>
    <div class="pdfPreview-header"></div>

    <div class="pdfPreview-body">
        <?php $this->insert('view', $this->all()); ?>
    </div>

    <div class="pdfPreview-footer">
        <?php $this->insert('nav', $this->all()); ?>
    </div>
</div>

<?php echo $this->after(); ?>
