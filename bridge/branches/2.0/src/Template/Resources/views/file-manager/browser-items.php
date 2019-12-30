<?php
/**
 * Gestionnaire de fichiers > Explorateur de fichiers | Liste des éléments.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\View $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileCollection $files
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
?>
<ul class="FileManager-browserItems" data-control="file-manager.browser.items">
    <?php foreach ($files as $file) : ?>
        <li class="FileManager-browserItem FileManager-browserItem--<?php echo $file->isDir() ? 'dir': 'file';?>"
            data-control="file-manager.browser.item">
            <?php $this->insert('browser-item', compact('file')); ?>
        </li>
    <?php endforeach; ?>
</ul>