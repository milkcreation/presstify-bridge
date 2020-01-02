<?php
/**
 * Gestionnaire de fichiers > Prévisualisation de fichier.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\View $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
echo partial('tag', [
    'tag'     => 'span',
    'attrs'   => [
        'class' => 'FileManager-preview FileManager-preview--default'
    ],
    'content' => $file->getIcon()
]);