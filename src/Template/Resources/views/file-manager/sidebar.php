<?php
/**
 * Gestionnaire de fichiers > Barre latérale.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\View $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
?>
<div class="FileManager-sidebarInfos" data-control="file-manager.sidebar.file-infos">
    <h3 class="FileManager-title"><?php _e('Élèment sélectionné', 'tify'); ?></h3>
    <?php $this->insert('file-infos', compact('file')); ?>
</div>

<h3 class="FileManager-title"><?php _e('Répertoire courant', 'tify'); ?></h3>

<ul class="FileManager-sidebarActions">
    <li class="FileManager-sidebarAction FileManager-sidebarAction--create">
        <?php $this->insert('action-create', compact('file')); ?>
        <div class="FileManager-sidebarActionButton">
            <a href="#"
               class="FileManager-button FileManager-button--toggle"
               data-control="file-manager.action.toggle"
               data-action="create"
            ><?php _e('Créer un dossier', 'tify'); ?></a>
        </div>
    </li>
    <li class="FileManager-sidebarAction FileManager-sidebarAction--upload">
        <?php $this->insert('action-upload', compact('file')); ?>
        <div class="FileManager-sidebarActionButton">
            <a href="#"
               class="FileManager-button FileManager-button--toggle"
               data-control="file-manager.action.toggle"
               data-action="upload"
            ><?php _e('Ajouter des fichiers', 'tify'); ?></a>
        </div>
    </li>
</ul>
