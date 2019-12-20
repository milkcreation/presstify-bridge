<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 */
echo partial('tag', [
    'attrs'   => [
        'data-control' => 'metabox-imagefeed.addnew',
    ],
    'content' => _n(
        __('Ajouter une image', 'tify'),
        __('Ajouter des images', 'tify'),
        (($this->get('max', -1) === 1) ? 1 : 2),
        'tify'
    ),
    'tag'     => 'button',
]);