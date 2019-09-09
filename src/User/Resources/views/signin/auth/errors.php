<?php
/**
 * Formulaire d'authentification | Notifications > liste des erreurs.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\User\Signin\SigninView $this
 * @var array $errors
 */
?>
<ol class="Signin-authNoticesItems Signin-authErrorsItems">
    <?php foreach ($errors as $error) : ?>
        <li class="Signin-authNoticesItem Signin-authErrorsItem"><?php echo $error; ?></li>
    <?php endforeach; ?>
</ol>