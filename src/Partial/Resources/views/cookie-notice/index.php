<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>
<?php $this->before(); ?>
<?php echo partial('notice', [
    'attrs'   => $this->get('attrs', []),
    'content' => $this->get('content', ''),
    'dismiss' => $this->get('dismiss', '')
]); ?>
<?php $this->after();