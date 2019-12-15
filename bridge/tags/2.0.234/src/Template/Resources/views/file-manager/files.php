<?php
/**
 * Gestionnaire de fichiers > Fichier.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\Viewer $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo[] $files
 */
?>
<ul class="FileManager-contentFiles" data-control="file-manager.content.items">
    <?php foreach ($files as $file) : ?>
        <li class="FileManager-contentFile" data-control="file-manager.content.item">
            <?php $this->insert('file', compact('file')); ?>
        </li>
    <?php endforeach; ?>
</ul>