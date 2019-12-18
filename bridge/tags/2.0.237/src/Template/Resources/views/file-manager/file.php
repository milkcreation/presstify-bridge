<?php
/**
 * Gestionnaire de fichiers > Fichier.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\Viewer $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
?>
<a href="<?php echo $file->getUrl(); ?>"
   data-path="<?php echo $file->getRelPath(); ?>"
   data-control="file-manager.action.get"
   class="FileManager-contentFileLink FileManager-contentFileLink--<?php echo($file->isDir() ? 'dir' : 'file'); ?>"
>
    <div class="FileManager-contentFileAttr FileManager-contentFileAttr--preview">
        <?php echo $file->getIcon(); ?>
    </div>

    <div class="FileManager-contentFileAttr FileManager-contentFileAttr--name">
        <?php echo $file->getBasename(); ?>
    </div>

    <div class="FileManager-contentFileAttr FileManager-contentFileAttr--size">
        <?php echo $file->getHumanSize(); ?>
    </div>

    <div class="FileManager-contentFileAttr FileManager-contentFileAttr--date">
        <?php echo $file->getHumanDate('d/m/Y H:i'); ?>
    </div>
</a>