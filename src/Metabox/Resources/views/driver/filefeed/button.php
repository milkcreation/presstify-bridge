<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 */
echo partial('tag', [
    'attrs'   => [
        'data-control' => 'metabox-filefeed.addnew',
    ],
    'content' => _n(
        __('Ajouter un fichier', 'tify'),
        __('Ajouter des fichiers', 'tify'),
        (($this->get('max', -1) === 1) ? 1 : 2),
        'tify'
    ),
    'tag'     => 'button',
]);
