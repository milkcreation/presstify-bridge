<?php
/**
 * Formulaire d'authentification | Notifications > liste des informations.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\User\Signin\SigninView $this
 * @var array $infos
 */
?>
<ol class="Signin-authNoticesItems Signin-authInfosItems">
    <?php foreach($infos as $info) : ?>
        <li class="Signin-authNoticesItem Signin-authInfosItem"><?php echo $info; ?></li>
    <?php endforeach; ?>
</ol>