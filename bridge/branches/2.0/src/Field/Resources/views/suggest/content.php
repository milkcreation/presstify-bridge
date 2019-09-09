<?php
/**
 * @var tiFy\Field\FieldView $this
 */
$this->insert('input', $this->all());

if ($this->get('alt')) {
    $this->insert('alt', $this->all());
}

if ($this->get('spinner')) {
    $this->insert('spinner', $this->all());
}