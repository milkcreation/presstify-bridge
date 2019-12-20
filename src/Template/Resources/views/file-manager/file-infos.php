<?php
/**
 * Gestionnaire de fichiers > Informations du fichier.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\Viewer $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
?>
<div class="FileManager-finfo">
    <div class="FileManager-finfoPreview">
        <?php echo $this->preview($file); ?>
    </div>
    <?php if ($file->isFile()) : ?>
    <ul class="FileManager-finfoHandlers">
        <li class="FileManager-finfoHandler FileManager-finfoHandler--download">
            <a href="<?php echo $file->getDownloadUrl(); ?>"
               class="FileManager-finfoHandlerLink"
               data-control="file-manager.handler.download"
            >
                <?php echo $this->getIcon('download'); ?><?php _e('Télécharger', 'tify'); ?>
            </a>
        </li>
    </ul>
    <?php endif; ?>
    <ul class="FileManager-finfoAttrs">
        <li class="FileManager-finfoAttr FileManager-finfoAttr--name">
            <label class="FileManager-finfoAttrLabel"><?php _e('Nom :', 'tify'); ?></label>
            <span class="FileManager-finfoAttrValue"><?php echo $file->getBasename(); ?></span>
        </li>
        <li class="FileManager-finfoAttr FileManager-finfoAttr--type">
            <label class="FileManager-finfoAttrLabel"><?php _e('Type :', 'tify'); ?></label>
            <span class="FileManager-finfoAttrValue"><?php echo $file->getHumanType(); ?></span>
        </li>
        <li class="FileManager-finfoAttr FileManager-finfoAttr--ext">
            <label class="FileManager-finfoAttrLabel"><?php _e('Type de médias :', 'tify'); ?></label>
            <span class="FileManager-finfoAttrValue"><?php echo $file->getMimetype(); ?></span>
        </li>
        <li class="FileManager-finfoAttr FileManager-finfoAttr--size">
            <label class="FileManager-finfoAttrLabel"><?php _e('Taille :', 'tify'); ?></label>
            <span class="FileManager-finfoAttrValue"><?php echo $file->getHumanSize(); ?></span>
        </li>
        <li class="FileManager-finfoAttr FileManager-finfoAttr--date">
            <label class="FileManager-finfoAttrLabel"><?php _e('Date :', 'tify'); ?></label>
            <span class="FileManager-finfoAttrValue"><?php echo $file->getHumanDate('d/m/Y'); ?></span>
        </li>
        <?php /* if ($file->isLocal()) : ?>
            <li class="FileManager-finfoAttr FileManager-finfoAttr--ctime">
                <label class="FileManager-finfoAttrLabel"><?php _e('Création :', 'tify'); ?></label>
                <span class="FileManager-finfoAttrValue"><?php echo $file->getHumanDate('d/m/Y'); ?></span>
            </li>
            <li class="FileManager-finfoAttr FileManager-finfoAttr--mtime">
                <label class="FileManager-finfoAttrLabel"><?php _e('Modification :', 'tify'); ?></label>
                <span class="FileManager-finfoAttrValue"><?php echo $file->getMTime(); ?></span>
            </li>
            <li class="FileManager-finfoAttr FileManager-finfoAttr--owner">
                <label class="FileManager-finfoAttrLabel"><?php _e('Propriétaire :', 'tify'); ?></label>
                <span class="FileManager-finfoAttrValue"><?php echo $file->getOwner(); ?></span>
            </li>
            <li class="FileManager-finfoAttr FileManager-finfoAttr--group">
                <label class="FileManager-finfoAttrLabel"><?php _e('Groupe :', 'tify'); ?></label>
                <span class="FileManager-finfoAttrValue"><?php echo $file->getGroup(); ?></span>
            </li>
        <?php endif; */ ?>
    </ul>
    <?php if (!$file->isRoot()) : ?>
        <ul class="FileManager-finfoActions">
            <li class="FileManager-finfoAction FileManager-finfoAction--rename">
                <?php $this->insert('action-rename', compact('file')); ?>
                <div class="FileManager-finfoActionButton">
                    <a href="#"
                       class="FileManager-button FileManager-button--toggle"
                       data-control="file-manager.action.toggle"
                       data-action="rename"
                    ><?php _e('Renommer', 'tify'); ?></a>
                </div>
            </li>

            <li class="FileManager-finfoAction FileManager-finfoAction--delete">
                <?php $this->insert('action-delete', compact('file')); ?>
                <div class="FileManager-finfoActionButton">
                    <a href="#"
                       class="FileManager-button FileManager-button--toggle"
                       data-control="file-manager.action.toggle"
                       data-action="delete"
                    ><?php _e('Supprimer', 'tify'); ?></a>
                </div>
            </li>
        </ul>
    <?php endif; ?>
</div>