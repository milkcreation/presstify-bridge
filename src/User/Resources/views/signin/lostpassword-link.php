<?php
/**
 * Authentification | Lien vers l'interface de mot de passe oublié.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\User\Signin\SigninView $this
 */
?>
<a  href="<?php echo wp_lostpassword_url($this->get('lost_password_link.redirect', '')); ?>\"
    title="<?php echo $this->get('lost_password_link.title', __('Récupération de mot de passe perdu', 'tify')); ?>"
    class="Signin-lostPasswordLink"
>
    <?php echo $this->get('lost_password_link.content', ''); ?>
</a>