<?php

namespace tiFy\Form\Addon\Mailer;

class MailerOptionsNotification extends AbstractMailerOptions
{
    /**
     * {@inheritdoc}
     */
    public function header($args = null, $null1 = null, $null2 = null)
    {
        return __('Notification', 'tify');
    }

    /**
     * {@inheritdoc}
     */
    public function content($args = null, $null1 = null, $null2 = null)
    {
        $option_names = $this->optionNames;

        $option_values = [
            'notification' => get_option($option_names['notification'], 'off') ?: 'off',
            'recipients'   => get_option($option_names['recipients']) ?: [],
        ];

        return $this->viewer(
            'addon/mailer/admin/notification',
            compact('option_names', 'option_values')
        );
    }

    /**
     * Vérification du format de l'email du destinataire de notification
     *
     * @param array $recipients Attributs des destinataires
     *
     * @return array
     */
    public function sanitize_recipients($recipients)
    {
        if ($recipients) :
            foreach ($recipients as $recipient => $recip) :
                if (empty($recip['email'])) :
                    add_settings_error(
                        $this->getObjectName(),
                        $recipient . '-email_empty',
                        __(
                            'L\'email du destinataire des messages de notification ne peut être vide',
                            'theme'
                        )
                    );
                elseif (!is_email($recip['email'])) :
                    add_settings_error(
                        $this->getObjectName(),
                        $recipient . '-email_format',
                        __(
                            'Le format de l\'email du destinataire des messages de notification #%d ' .
                            'n\'est pas valide',
                            'theme'
                        )
                    );
                endif;
            endforeach;
        endif;

        return $recipients;
    }

    /**
     * {@inheritdoc}
     */
    public function settings()
    {
        return [
            $this->optionNames['notification'],
            $this->optionNames['recipients'] => [
                'sanitize_callback' => [$this, 'sanitize_recipients']
            ]
        ];
    }
}