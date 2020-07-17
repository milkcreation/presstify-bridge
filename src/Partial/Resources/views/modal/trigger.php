<?php
/**
 * @var tiFy\Partial\PartialView $this .
 */
echo partial('tag', [
    'tag'     => $this->get('tag'),
    'attrs'   => $this->get('attrs'),
    'content' => $this->get('content'),
]);