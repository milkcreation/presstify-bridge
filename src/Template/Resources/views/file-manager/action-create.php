<?php
/**
 * Gestionnaire de fichiers > Formulaire de création d'un nouveau répertoire.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\View $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
?>
<div class="FileManager-action FileManager-action--toggleable FileManager-action--create"
     data-control="file-manager.action.create"
>
    <h3 class="FileManager-title"><?php _e('Créer un dossier', 'tify'); ?></h3>

    <div class="FileManager-actionNotices"></div>

    <form class="FileManager-actionForm" method="post" action="" data-control="file-manager.action.create.form">
        <div class="FileManager-actionFormFields">
            <?php echo field('hidden', ['name'  => 'path', 'value' => $file->getRelPath()]); ?>

            <?php echo field('text', [
                'name'  => 'name',
                'attrs' => [
                    'placeholder' => __('Saisissez le nom du dossier ...', 'tify')
                ]
            ]); ?>
        </div>

        <div class="FileManager-actionFormButtons">
            <?php echo field('button', [
                'attrs'   => [
                    'class' => 'FileManager-button FileManager-button--valid FileManager-actionButton'
                ],
                'type'    => 'submit',
                'content' => __('Valider', 'tify')
            ]); ?>

            <?php echo field('button', [
                'attrs'   => [
                    'class'        => 'FileManager-button FileManager-button--cancel FileManager-actionButton',
                    'data-control' => 'file-manager.action.toggle',
                    'data-action'  => 'create',
                    'data-reset'   => 'true'
                ],
                'type'    => 'button',
                'content' => __('Annuler', 'tify')
            ]); ?>
        </div>
    </form>

    <a href="#"
       class="FileManager-actionClose"
       data-control="file-manager.action.toggle"
       data-action="create">
        <?php echo $this->getIcon('close'); ?>
    </a>
</div>