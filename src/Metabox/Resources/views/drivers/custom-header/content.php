<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 */
echo field('media-image', array_merge($this->params(), [
    'name'  => $this->name(),
    'value' => $this->value(),
]));