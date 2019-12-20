<?php
/**
 * Message de succès.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var string[] $messages
 */
?>
<ol class="Form-noticeItems Form-noticeItems--success">
    <?php foreach ($messages as $message) : ?>
        <li class="Form-noticeItem Form-noticeItem--success"><?php echo $message; ?></li>
    <?php endforeach; ?>
</ol>