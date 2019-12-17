<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 */
echo field('select-image', array_merge($this->params(), [
    'name'    => $this->get('name'),
    'value'   => $this->get('value', '')
]));