<?php
/**
 * Formulaire d'authentification | Champs > Bouton de soumission.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\User\Signin\SigninView $this
 */
?>
<p class="Signin-field Signin-field--submit">
    <?php echo field('button', $this->get('auth.fields.submit')); ?>
</p>
