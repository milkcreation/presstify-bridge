<?php
/**
 * @var tiFy\Field\FieldView $this
 */
?>
<?php $this->before(); ?>
<div <?php echo $this->htmlAttrs($this->get('container.attrs', [])); ?>>
    <div <?php $this->attrs(); ?>>
        <div class="tiFyField-fileJsFallback fallback" data-control="file-js.fallback">
            <?php echo field('file', ['name' => $this->getName(), 'multiple' => $this->get('multiple')]); ?>
        </div>
    </div>

    <div class="tiFyField-fileJsLegend" data-control="file-js.legend">
        <?php echo __('Cliquez sur la zone ou glisser/déposer des fichiers', 'tify'); ?>
    </div>
</div>
<?php $this->after();