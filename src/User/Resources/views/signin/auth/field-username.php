<?php
/**
 * Formulaire d'authentification | Champs > Identifiant de connexion.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\User\Signin\SigninView $this
 */
?>
<p class="Signin-field Signin-field--username">
    <?php if ($label = $this->get('auth.fields.username.label')) : ?>
        <?php echo field('label', $label); ?>
    <?php endif; ?>

    <?php echo field('text', $this->get('auth.fields.username')); ?>
</p>
