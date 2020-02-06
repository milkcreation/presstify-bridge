<?php
/**
 * Gestionnaire de fichiers > Formulaire de suppression d'un élement (fichier ou repertoire).
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\View $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
?>
<div class="FileManager-action FileManager-action--toggleable FileManager-action--delete"
     data-control="file-manager.action.delete"
>
    <h3 class="FileManager-title"><?php _e('Supprimer', 'tify'); ?></h3>

    <div class="FileManager-actionNotices">
        <?php if ($file->isDir()) : ?>
            <?php echo partial('notice', [
                'attrs' => [
                    'class' => 'FileManager-noticeMessage FileManager-noticeMessage--warning'
                ],
                'content' => __('<b>ATTENTION :</b> Vous vous apprêtez à supprimer un répertoire ainsi ' .
                    'que l\'ensemble des fichiers et dossiers qu\'il contient. ' .
                    'Ils ne pourront être récupérés. <br><b>Êtes vous sûr ?</b>', 'tify'),
                'type'    => 'warning'
            ]);
            ?>
        <?php else: ?>
            <?php echo partial('notice', [
                'attrs' => [
                    'class' => 'FileManager-noticeMessage FileManager-noticeMessage--warning'
                ],
                'content' => __('<b>ATTENTION :</b> Vous vous apprêtez à supprimer un fichier. '.
                    'Il ne pourra être récupéré. <br><b>Êtes vous sûr ?</b>', 'tify'),
                'type'    => 'warning',
            ]);
            ?>
        <?php endif; ?>
    </div>
    <form class="FileManager-actionForm" method="post" action="" data-control="file-manager.action.delete.form">
        <div class="FileManager-actionFormFields">
            <?php echo field('hidden', ['name'  => 'path', 'value' => $file->getRelPath()]); ?>
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
                    'class' => 'FileManager-button FileManager-button--cancel FileManager-actionButton',
                    'data-control' => 'file-manager.action.toggle',
                    'data-action'  => 'delete',
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
       data-action="delete">
        <?php echo $this->getIcon('close'); ?>
    </a>
</div>