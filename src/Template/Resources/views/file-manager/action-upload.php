<?php
/**
 * Gestionnaire de fichiers > Formulaire de création d'un nouveau répertoire.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\View $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
?>
<div class="FileManager-action FileManager-action--toggleable FileManager-action--upload"
     data-control="file-manager.action.upload"
>
    <h3 class="FileManager-title"><?php _e('Ajouter des fichiers', 'tify'); ?></h3>

    <div class="FileManager-actionNotices"></div>

    <div class="FileManager-actionContainer">
        <form action=""
              enctype="multipart/form-data"
              class="FileManager-actionForm FileManager-actionForm--upload"
              data-control="file-manager.action.upload.form"
        >
            <div class="FileManager-actionFormFields">
                <?php echo field('hidden', ['name' => 'action', 'value' => 'upload']); ?>
                <?php echo field('hidden', ['name'  => 'path', 'value' => $file->getRelPath()]); ?>
            </div>
            <div class="FileManager-actionFormFallback fallback">
                <input name="file" type="file" multiple />
            </div>
        </form>
        <div class="FileManager-actionFormLegend">
            <?php echo $this->getIcon('upload') . __('Cliquez sur la zone ou glisser/déposer des fichiers', 'tify'); ?>
        </div>
    </div>

    <a href="#"
       class="FileManager-actionClose"
       data-control="file-manager.action.toggle"
       data-action="upload">
        <?php echo $this->getIcon('close'); ?>
    </a>
</div>