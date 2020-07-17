<?php
/**
 * @var tiFy\Contracts\Partial\PartialView $this
 * @var array $types
 */
?>
<?php foreach($types as $type) : ?>
    <?php foreach ($this->get($type, []) as $notice) : ?>
        <?php echo partial('notice', array_merge($notice['attrs'] ? : [], [
            'type'      => $type,
            'content'   => $notice['message']
        ])); ?>
    <?php endforeach; ?>
<?php endforeach;