<?php
/**
 * Messages d'erreurs.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var string[] $messages
 */
?>
<ol class="Form-noticeItems Form-noticeItems--error">
    <?php foreach ($messages as $message) : ?>
        <li class="Form-noticeItem Form-noticeItem--error"><?php echo $message; ?></li>
    <?php endforeach; ?>
</ol>