<?php
/**
 * @var tiFy\Mail\MailView $this
 */
?>
<tr class="rowBodyContent">
    <td>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr class="rowBodyContent-section rowBodyContent-section--header">
                <td>
                    <h1>
                        <?php _e('Test d\'envoi de mail depuis le site :', 'tify');
                        ?>
                    </h1>
                    <h2>
                        <?php echo get_bloginfo('blogname'); ?>
                    </h2>
                </td>
            </tr>

            <tr class="rowBodyContent-section rowBodyContent-section--body">
                <td>
                    <p>
                        <?php _e(
                            'Si ce mail vous est parvenu c\'est qu\'un test d\'expédition a été envoyé depuis le ' .
                            'site : ', 'theme'
                        ); ?>
                    </p>
                    <p>
                        <?php echo partial('tag', [
                            'attrs'   => [
                                'clicktracking' => 'off',
                                'href'          => home_url('/'),
                                'target'        => '_blank',
                                'title'         => sprintf(
                                    __('Lien vers le site internet - %s', 'tify'),
                                    get_bloginfo('blogname')
                                ),
                            ],
                            'content' => get_bloginfo('blogname'),
                            'tag'     => 'a',
                        ]); ?>
                    </p>
                    <p>
                        <?php _e(
                            'Néanmoins, il pourrait s\'agir d\'une erreur. ' .
                            'Si vous n\'êtes pas concerné par cet e-mail, ' .
                            'vous pouvez prendre contact avec l\'administrateur du site à cette adresse :',
                            'theme'
                        ); ?>
                    </p>
                    <p>
                        <?php echo partial('tag', [
                            'attrs'   => [
                                'clicktracking' => 'off',
                                'href'          => 'mailto:' . get_option('admin_email'),
                                'target'        => '_blank',
                                'title'         => sprintf(
                                    __('Contacter l\'administrateur du site - %s', 'theme'),
                                    get_bloginfo('blogname')
                                ),
                            ],
                            'content' => get_option('admin_email'),
                            'tag'     => 'a',
                        ]); ?>
                    </p>
                    <br>
                    <p><?php _e('Merci de votre compréhension.', 'tify'); ?></p>
                </td>
            </tr>

            <tr class="rowBodyContent-section rowBodyContent-section--footer">
                <td>
                    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0"
                           style="margin: auto;">
                        <tr>
                            <td>
                                <?php echo partial('tag', [
                                    'attrs'   => [
                                        'clicktracking' => 'off',
                                        'href'          => home_url('/'),
                                        'target'        => '_blank',
                                        'title'         => sprintf(
                                            __('Visiter le site - %s', 'theme'),
                                            get_bloginfo('blogname')
                                        ),
                                    ],
                                    'content' => __('Visiter le site', 'theme'),
                                    'tag'     => 'a',
                                ]); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>