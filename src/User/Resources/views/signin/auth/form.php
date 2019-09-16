<?php
/**
 * Formulaire d'authentification | Gabarit.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\User\Signin\SigninView $this
 */
?>
<?php $this->insert('auth/before', $this->all()); ?>

    <form <?php echo $this->htmlAttrs($this->get('auth.attrs')); ?>>
        <?php
        echo field('hidden', [
            'name'  => 'signin',
            'value' => $this->get('name'),
        ]);
        ?>

        <?php
        echo field('hidden', [
            'name'  => '_wpnonce',
            'value' => wp_create_nonce('Signin-login-' . $this->get('name')),
        ]);
        ?>

        <?php $this->insert('auth/header', $this->all()); ?>

        <?php $this->insert('auth/body', $this->all()); ?>

        <?php $this->insert('auth/footer', $this->all()); ?>
    </form>

<?php $this->insert('auth/after', $this->all());