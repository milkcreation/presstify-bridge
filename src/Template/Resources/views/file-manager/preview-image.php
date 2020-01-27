<?php
/**
 * Gestionnaire de fichiers > Prévisualisation de fichier image.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\View $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
?>
<?php $this->insert('spinner'); ?>
<?php echo partial('tag', [
    'tag'   => 'img',
    'attrs' => [
        'alt'   => $file->getBasename(),
        'class' => 'FileManager-preview FileManager-preview--image',
        'src'   => $file->getUrl()
    ]
]); ?>

<?php echo partial('tag', [
    'tag'     => 'a',
    'attrs'   => [
        'class'  => 'FileManager-button FileManager-button--fullscreen',
        'href'   => $file->getUrl(),
        'target' => '_blank'
    ],
    'content' => $this->getIcon('fullscreen')
]);