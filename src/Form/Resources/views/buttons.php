<?php
/**
 * Liste des boutons du formulaire.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var tiFy\Contracts\Form\ButtonController[] $buttons
 */
?>
<?php if ($buttons) : ?>
    <div class="Form-buttons">
        <?php foreach ($buttons as $button) : ?>
            <?php $this->insert('button', compact('button')); ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>