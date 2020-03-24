<?php
/**
 * Message de succès.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var string[] $messages
 */
?>
<ol class="FormNotice-items FormNotice-items--success">
    <?php foreach ($messages as $message) : ?>
        <li class="FormNotice-item FormNotice-item--success"><?php echo $message; ?></li>
    <?php endforeach; ?>
</ol>