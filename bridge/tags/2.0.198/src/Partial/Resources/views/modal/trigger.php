<?php
/**
 * @var tiFy\Partial\PartialView $this .
 */
?>
<?php
echo partial('tag', [
    'tag'     => $this->get('tag'),
    'attrs'   => $this->get('attrs'),
    'content' => $this->get('content'),
]);