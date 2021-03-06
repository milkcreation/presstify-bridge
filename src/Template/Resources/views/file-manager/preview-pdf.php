<?php
/**
 * Gestionnaire de fichiers > PrĂ©visualisation de fichier pdf.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\View $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
?>
<?php $this->insert('spinner'); ?>

<?php echo partial('pdfviewer', [
    'src'   => isset($file)
        ? $file->getUrl()
        : '7855ce7d975d5a1ede9b5a83d7235dee/document-manager/cache/Symfony_quick_tour_4.2.pdf',
    'attrs' => [
        'class' => 'FileManager-preview FileManager-preview--pdf'
    ],
    'prev'  => [
        'attrs'   => [
            'class' => '%s FileManager-button',
        ],
        'content' => $this->getIcon('prev')
    ],
    'next'  => [
        'attrs'   => [
            'class' => '%s FileManager-button',
        ],
        'content' => $this->getIcon('next')
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