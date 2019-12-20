<?php
/**
 * Gestionnaire de fichiers > Fil d'ariane.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\Viewer $this
 * @var tiFy\Template\Templates\FileManager\Contracts\Breadcrumb|iterable $items
 */
?>
<ol class="FileManager-breadcrumb" data-control="file-manager.breadcrumb">
    <li class="FileManager-breadcrumbPart">
        <a href="#"
           class="FileManager-breadcrumbPartLink"
           data-control="file-manager.action.get"
           data-path="/"
        >
            <?php echo $this->getIcon('home'); ?>
        </a>
    </li>

    <?php foreach($items as $path => $name) : ?>
        <li class="FileManager-breadcrumbPart">
            <a href="#"
               class="FileManager-breadcrumbPartLink"
               data-control="file-manager.action.get"
               data-path="<?php echo $path; ?>"
            >
                <?php echo $name; ?>
            </a>
        </li>
    <?php endforeach; ?>
</ol>