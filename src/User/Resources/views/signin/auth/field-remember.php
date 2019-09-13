<?php
/**
 * Formulaire d'authentification | Champs > Se souvenir.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\User\Signin\SigninView $this
 */
?>
<?php if ($this->get('auth.fields.remember')) : ?>
<p class="Signin-field Signin-field--remember">
    <?php echo field('checkbox', $this->get('auth.fields.remember')); ?>

    <?php if ($label = $this->get('auth.fields.remember.label')) : ?>
        <?php echo field('label', $label); ?>
    <?php endif; ?>
</p>
<?php endif; ?>