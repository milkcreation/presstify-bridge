<?php
/**
 * Gestionnaire de fichiers > Bouton de bascule du type de vue.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\View $this
 */
?>
<ul class="FileManager-switcher" data-control="file-manager.switcher">
    <li class="FileManager-switch FileManager-switch--grid selected">
        <a href="#" class="FileManager-switchLink" data-control="file-manager.view.toggle" data-view="grid">
            <?php echo $this->getIcon('grid'); ?>
        </a>
    </li>

    <li class="FileManager-switch FileManager-switch--list">
        <a href="#" class="FileManager-switchLink" data-control="file-manager.view.toggle" data-view="list">
            <?php echo $this->getIcon('list'); ?>
        </a>
    </li>
</ul>