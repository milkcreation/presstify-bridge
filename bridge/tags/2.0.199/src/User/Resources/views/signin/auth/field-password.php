<?php
/**
 * Formulaire d'authentification | Champs > Mot de passe de connexion.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\User\Signin\SigninView $this
 */
?>
<p class="Signin-field Signin-field--password">
    <?php if ($label = $this->get('auth.fields.password.label')) : ?>
        <?php echo field('label', $label); ?>
    <?php endif; ?>

    <?php echo field('password', $this->get('auth.fields.password')); ?>
</p>
