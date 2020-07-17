<?php
/**
 * @var tiFy\Contracts\Mail\MailView $this
 */
echo sprintf(__('Ceci est un test d\'envoi de mail depuis le site %s', 'tify'), get_bloginfo('blogname')) . "\n\n" .
__('Si ce mail, vous est parvenu c\'est qu\'il vous a été expédié depuis le site : ') . "\n" .
site_url('/') . "\n\n" .
__('Néanmoins, il pourrait s\'agir d\'une erreur. Si vous n\'êtes pas concerné par cet e-mail, ','tify') . "\n" .
__('vous pouvez prendre contact avec l\'administrateur du site à cette adresse : ', 'tify') . "\n" .
get_option('admin_email') . "\n\n" .
__('Merci de votre compréhension', 'tify');
