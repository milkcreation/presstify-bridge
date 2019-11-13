<?php
/**
 * Bouton de formulaire.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\Form\FactoryView $this
 * @var tiFy\Contracts\Form\ButtonController $button
 */
?>
<?php echo $button->get('before'); ?>
<?php if ($button->hasWrapper()) :
    echo partial('tag', array_merge(
        $button->get('wrapper', []),
        [
            'content' => (string) $button
        ]
    ));
else :
    echo $button;
endif; ?>
<?php echo $button->get('after'); ?>