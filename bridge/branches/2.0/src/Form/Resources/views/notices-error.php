<?php
/**
 * Messages d'erreurs.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var string[] $messages
 */
?>
<ol class="FormNotice-items FormNotice-items--error">
    <?php foreach ($messages as $message) : ?>
        <li class="FormNotice-item FormNotice-item--error"><?php echo $message; ?></li>
    <?php endforeach; ?>
</ol>