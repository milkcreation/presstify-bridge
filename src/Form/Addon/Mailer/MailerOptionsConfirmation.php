<?php

namespace tiFy\Form\Addon\Mailer;

class MailerOptionsConfirmation extends AbstractMailerOptions
{
    /**
     * {@inheritdoc}
     */
    public function header($args = null, $null1 = null, $null2 = null)
    {
        return __('Confirmation', 'tify');
    }

    /**
     * {@inheritdoc}
     */
    public function content($args = null, $null1 = null, $null2 = null)
    {
        $option_names = $this->optionNames;

        $option_values = [
            'confirmation' => get_option($option_names['confirmation'], 'off') ?: 'off',
            'sender'       => array_merge(
                [
                    'email' => get_option('admin_email'),
                    'name'  => ''
                ],
                get_option($option_names['sender']) ?: []
            )
        ];

        return $this->viewer(
            'addon/mailer/admin/confirmation',
            compact('option_names', 'option_values')
        );
    }

    /**
     * Vérification du format de l'email de l'expéditeur
     *
     * @param array $sender Attributs de l'expéditeur
     *
     * @return array

    public function sanitize_sender($sender)
    {
        if (empty($sender['email'])) :
            add_settings_error(
                $this->getObjectName(),
                'sender-email_empty',
                sprintf(
                    __('L\'email "%s" ne peut être vide', 'theme'),
                    __('Expéditeur du message de confirmation de reception', 'theme')
                )
            );
        elseif (!is_email($sender['email'])) :
            add_settings_error(
                $this->getObjectName(),
                'sender-email_format',
                sprintf(
                    __('Le format de l\'email "%s" n\'est pas valide', 'theme'),
                    __('Expéditeur du message de confirmation de reception', 'theme')
                )
            );
        endif;

        return $sender;
    }  */

    /**
     * {@inheritdoc}
     */
    public function settings()
    {
        return [
            $this->optionNames['confirmation'],
            $this->optionNames['sender']     => [
                'sanitize_callback' => [$this, 'sanitize_sender']
            ]
        ];
    }
}