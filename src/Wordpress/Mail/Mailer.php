<?php

namespace tiFy\Wordpress\Mail;

use tiFy\Mail\Mailer as tiFyMailer;

class Mailer extends tiFyMailer
{
    /**
     * @inheritdoc
     */
    public function defaults()
    {
        $admin_email = get_option('admin_email');
        $admin_name = ($user = get_user_by('email', get_option('admin_email'))) ? $user->display_name : '';

        return array_merge(parent::defaults(), [
            'to'           => [$admin_email, $admin_name],
            'from'         => [$admin_email, $admin_name],
        ]);
    }
}