<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 */
echo field('text-remaining', array_merge($this->params(), [
    'name'  => $this->name(),
    'value' => $this->value(),
]));
