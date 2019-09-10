<?php
/**
 * Gestionnaire de fichiers.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\Viewer $this
 */
?>
<div class="wrap">
    <div class="FileManager" <?php echo $this->htmlAttrs($this->param('attrs', [])); ?>>
        <?php $this->insert('notice'); ?>

        <div class="FileManager-sidebar" data-control="file-manager.sidebar">
            <?php $this->insert('sidebar', ['file' => $this->getFile()]); ?>
        </div>

        <?php //$this->insert('browser'); ?>

        <div class="FileManager-content" data-control="file-manager.content" data-view="grid">
            <div class="FileManager-contentHeader">
                <?php $this->insert('breadcrumb', ['items' => $this->breadcrumb()]); ?>
                <?php $this->insert('switcher'); ?>
            </div>

            <div class="FileManager-contentBody">
                <?php if ($files = $this->getFiles()) : ?>
                    <?php $this->insert('files', compact('files')); ?>
                <?php endif; ?>
            </div>

            <div class="FileManager-contentFooter"></div>
        </div>
    </div>
</div>