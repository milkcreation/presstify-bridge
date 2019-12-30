<?php
/**
 * Gestionnaire de fichiers > Prévisualisation de fichier pdf.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\View $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
?>
<?php $this->insert('spinner'); ?>

<?php
echo partial('tag', [
    'tag'   => 'iframe',
    'attrs' => [
        'class'       => 'FileManager-preview FileManager-preview--document',
        'frameborder' => 0,
        'src'         => "//docs.google.com/viewer?embedded=true&hl=fr&url=" . $file->getUrl(true)
    ]
]);