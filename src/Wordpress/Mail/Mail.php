<?php declare(strict_types=1);

namespace tiFy\Wordpress\Mail;

use tiFy\Mail\Mailer;

class Mail
{
    /**
     * CONSTRUCTEUR
     *
     * @return void
     */
    public function __construct()
    {
        $admin_email = get_option('admin_email');
        $admin_name = ($user = get_user_by('email', get_option('admin_email'))) ? $user->display_name : '';

        Mailer::setDefaults([
            'to'           => [$admin_email, $admin_name],
            'from'         => [$admin_email, $admin_name],
            'subject'      => sprintf(__('Test d\'envoi de mail depuis le site %s', 'tify'), get_bloginfo('blogname')),
            'charset'      => get_bloginfo('charset'),
        ]);
    }
}