<?php
/**
 * Gestionnaire de fichiers > Explorateur de fichiers.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\Viewer $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
?>
<div class="FileManager-browser">
    <ul class="FileManager-browserItems" data-control="file-manager.browser.items">
        <li class="FileManager-browserItem FileManager-browserItem--dir" data-control="file-manager.browser.item">
            <a href="#"
               class="FileManager-browserItemContent"
               data-path="<?php echo $this->getFile('/')->getRelPath(); ?>"
               data-control="file-manager.browser.browse"
               aria-selected="true"
            >
                <?php echo $this->getIcon('collapse') . __('Racine', 'tify'); ?>
            </a>
            <?php if ($files = $this->getFiles()): ?>
                <?php $this->insert('browser-items', compact('files')); ?>
            <?php endif; ?>
        </li>
    </ul>
</div>
