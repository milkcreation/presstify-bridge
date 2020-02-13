<?php
/**
 * Formulaire d'authentification | Pied.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Auth\Signin\SigninView $this
 */
?>
<?php if ($this->get('auth.lost_password_link')) : ?>
    <?php $this->insert('lostpassword-link', $this->all()); ?>
<?php endif; ?>
