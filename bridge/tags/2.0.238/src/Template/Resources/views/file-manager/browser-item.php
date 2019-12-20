<?php
/**
 * Gestionnaire de fichiers > Explorateur de fichiers | Élèment.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\Viewer $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
?>
<?php if ($file->isDir()) : ?>
    <a href="#"
       class="FileManager-browserItemContent"
       data-path="<?php echo $file->getRelPath(); ?>"
       data-control="file-manager.browser.browse"
    >
        <?php echo $this->getIcon('expand') . $file->getBasename(); ?>
    </a>
<?php else : ?>
    <span class="FileManager-browserItemContent">
    <?php echo $file->getBasename(); ?>
</span>
<?php endif; ?>