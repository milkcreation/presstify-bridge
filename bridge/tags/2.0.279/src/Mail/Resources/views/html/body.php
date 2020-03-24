<?php
/**
 * @var tiFy\Contracts\Mail\MailView $this
 */
?>
<table cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" width="600" align="center">
    <tr>
        <td width="600">
            <div>
                <h1>
                    <?php
                    printf(
                        __('Test d\'envoi de mail depuis le site %s', 'tify'),
                        get_bloginfo('blogname')
                    );
                    ?>
                </h1>

                <p>
                    <?php
                    _e(
                        'Si ce mail, vous est parvenu c\'est qu\'un test d\'expédition a été envoyé ' .
                        'depuis le site : ',
                        'tify'
                    );
                    ?>
                </p>

                <p>
                    <a clicktracking=off href="<?php echo site_url('/'); ?>"
                       title="<?php
                       printf(
                           __('Lien vers le site internet - %s', 'tify'),
                           get_bloginfo('blogname')
                       );
                       ?>"
                    >
                        <?php echo get_bloginfo('blogname'); ?>
                    </a>
                </p>

                <p>
                    <?php
                    _e(
                        'Néanmoins, il pourrait s\'agir d\'une erreur. ' .
                        'Si vous n\'êtes pas concerné par cet e-mail, ' .
                        'vous pouvez prendre contact avec l\'administrateur du site à cette adresse :',
                        'tify'
                    );
                    ?>
                </p>

                <p>
                    <a href="mailto:<?php echo get_option('admin_email'); ?>"
                       title="<?php
                       printf(
                           __('Contacter l\'administrateur du site - %s', 'tify'),
                           get_bloginfo('blogname')
                       );
                       ?>"
                    >
                        <?php echo get_option('admin_email'); ?>
                    </a>
                </p>

                <br>

                <p><?php _e('Merci de votre compréhension', 'tify'); ?></p>
            </div>
        </td>
    </tr>
</table>
