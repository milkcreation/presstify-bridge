<?php
/**
 * Gestionnaire de fichiers > Prévisualisation de fichier vidéo.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\Viewer $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
?>
<?php $this->insert('spinner'); ?>

<?php
echo partial('tag', [
    'tag'     => 'video',
    'attrs'   => [
        'class'    => 'FileManager-preview FileManager-preview--video',
        'controls' => 'controls',
        'src'      => $file->getUrl()
    ],
    'content' => __('Votre navigateur de fichier n\'accepte pas ce type de fichier vidéo', 'tify')
]);