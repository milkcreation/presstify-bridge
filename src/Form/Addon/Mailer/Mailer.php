<?php declare(strict_types=1);

namespace tiFy\Form\Addon\Mailer;

use Closure;
use tiFy\Contracts\Form\FactoryField;
use tiFy\Form\AddonFactory;
use tiFy\Support\Proxy\{Mail, Metabox};

class Mailer extends AddonFactory
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        $this->form()->events()
            ->listen('request.proceed', function () {
                if ($debug = $this->params('debug')) {
                    $this->form()->events('addon.mailer.email.debug');
                }
            })
            ->listen('request.successed', function () {
                $this->form()->events('addon.mailer.email.send');
            })
            ->listen('addon.mailer.email.debug', [$this, 'emailDebug'])
            ->listen('addon.mailer.email.send', [$this, 'emailSend']);

        $prefix = $this->params('option_name_prefix', "FormMailer_{$this->form()->name()}");
        $option_names = $this->params('option_names', []);
        foreach (['confirmation', 'sender', 'notification', 'recipients'] as $option) {
            $option_names[$option] = $option_names[$option] ?? "{$prefix}{$option}";
        }
        $this->params(['option_names' => $option_names]);

        if ($this->params('confirmation') && get_option($option_names['confirmation'])) {
            $from = get_option($option_names['sender']);
            $from = is_array($from) ? $from : ['email' => '', 'name' => ''];

            $this->params(['confirmation.from' => [$from['email'], $from['name']]]);
        }

        if (
            $this->params('notification') &&
            get_option($option_names['notification']) &&
            ($to = get_option($option_names['recipients']))
        ) {
            array_walk($to, function (&$item) {
                $item = [$item['email'], $item['name']];
            });

            $this->params(['notification.to' => $to]);
        }

        if ($admin = $this->params('admin')) {
            $defaultAdmin = [
                'confirmation' => true,
                'notification' => true,
            ];

            $this->params(['admin' => is_array($admin) ? array_merge($defaultAdmin, $admin) : $defaultAdmin]);
        }

        if ($this->params('admin.confirmation') || $this->params('admin.notification')) {
            Metabox::add("FormAddonMailer-{$this->form()->name()}", [
                'title' => $this->form()->getTitle(),
            ])
                ->setScreen('tify_options@options')
                ->setContext('tab');

            if ($this->params('admin.confirmation')) {
                Metabox::add("FormAddonMailerConfirmation-{$this->form()->name()}", [
                    'driver'   => (new MailerConfirmationMetabox())->setAddon($this),
                    'parent'   => "FormAddonMailer-{$this->form()->name()}",
                    'position' => 1,
                ])
                    ->setScreen('tify_options@options')
                    ->setContext('tab');
            }

            if ($this->params('admin.notification')) {
                Metabox::add("FormAddonMailerNotification-{$this->form()->name()}", [
                    'driver'   => (new MailerNotificationMetabox())->setAddon($this),
                    'parent'   => "FormAddonMailer-{$this->form()->name()}",
                    'position' => 2,
                ])
                    ->setScreen('tify_options@options')
                    ->setContext('tab');
            }

            foreach ($option_names as $key => $option_name) {
                switch ($key) {
                    default:
                        register_setting('tify_options', $option_name);
                        break;
                    case 'recipients' :
                        register_setting('tify_options', $option_name, function ($recipients) {
                            if ($recipients) {
                                foreach ($recipients as $recipient => $recip) {
                                    if (empty($recip['email'])) {
                                        add_settings_error(
                                            'tify_options',
                                            $recipient . '-email_empty',
                                            __(
                                                'L\'email du destinataire des messages de notification ne peut ??tre vide',
                                                'tify'
                                            )
                                        );
                                    } elseif (!is_email($recip['email'])) {
                                        add_settings_error(
                                            'tify_options',
                                            $recipient . '-email_format',
                                            __(
                                                'Le format de l\'email du destinataire des messages de notification' .
                                                'n\'est pas valide',
                                                'tify'
                                            )
                                        );
                                    }
                                }
                            }

                            return $recipients;
                        });
                        break;
                    case 'sender' :
                        register_setting('tify_options', $option_name, function ($sender) {
                            if (empty($sender['email'])) {
                                add_settings_error(
                                    'tify_options',
                                    'sender-email_empty',
                                    sprintf(
                                        __('L\'email "%s" ne peut ??tre vide', 'tify'),
                                        __('Exp??diteur du message de confirmation de reception', 'tify')
                                    )
                                );
                            } elseif (!is_email($sender['email'])) {
                                add_settings_error(
                                    'tify_options',
                                    'sender-email_format',
                                    sprintf(
                                        __('Le format de l\'email "%s" n\'est pas valide', 'tify'),
                                        __('Exp??diteur du message de confirmation de reception', 'tify')
                                    )
                                );
                            }

                            return $sender;
                        });
                        break;
                }
            }
        }
    }

    /**
     * @inheritDoc
     *
     * @var array {
     * @var bool|array $admin {
     *      Affichage de l'intitul?? et de la valeur de saisie du champ dans le corps du mail
     *
     * @var bool $confirmation Activation de l'interface d'administration de l'email de confirmation de
     *      reception ?? destination des utilisateurs.
     * @var bool $notification Activation de l'interface d'administration de l'email de notification ??
     *      destination des administrateurs de site
     * }
     *
     * @var bool|array $confirmation Attributs de configuration d'exp??dition de l'email de confirmation de
     *      reception ?? destination des utilisateurs.
     *
     * @var bool|array $notification Attributs de configuration d'exp??dition de l'email de notification ??
     *      destination des administrateurs de site.
     *
     * @var string $option_name_prefix Prefixe du nom d'enregistrement des options d'exp??dition de mail (usage
     *      avanc??).
     * @var array $option_names (usage avanc??) Cartographie nom d'enregistrement des options en base.
     *
     * @var bool|string $debug Affichage du mail au lieu de l'exp??dition
     *      (false|'confirmation'|'notification')
     * }
     */
    public function defaultsParams(): array
    {
        return [
            'admin'              => true,
            'debug'              => false,
            'notification'       => [
                'subject' => sprintf(
                    __('Vous avez une nouvelle demande de contact sur le site %s', 'tify'),
                    get_bloginfo('name')
                ),
            ],
            'confirmation'       => [
                'subject' => sprintf(
                    __('Votre demande de contact sur le site %s', 'tify'),
                    get_bloginfo('name')
                ),
                'to'      => '%%email%%',
            ],
            'option_name_prefix' => '',
            'option_names'       => [],
        ];
    }

    /**
     * @inheritdoc
     */
    public function defaultsFieldOptions(): array
    {
        return [
            'show'  => true,
            'label' => function (FactoryField $field) {
                return $field->getTitle();
            },
            'value' => function (FactoryField $field) {
                return $field->getValues();
            },
        ];
    }

    /**
     * D??bogguage des emails de confirmation et/ou de notification.
     *
     * @return void
     */
    public function emailDebug()
    {
        switch ($this->params('debug')) {
            default:
            case 'confirmation' :
                if ($params = $this->parseMail($this->params('confirmation', []), 'confirmation')) {
                    Mail::debug($params);
                } else {
                    wp_die(
                        __('Email de confirmation non configur??.', 'tify'),
                        __('FormAddonMailer - Erreur', 'tify'),
                        500
                    );
                }
                break;

            case 'notification' :
                if ($params = $this->parseMail($this->params('notification', []), 'notification')) {
                    Mail::debug($params);
                } else {
                    wp_die(
                        __('Email de notification non configur??.', 'tify'),
                        __('FormAddonMailer - Erreur', 'tify'),
                        500
                    );
                }
                break;
        }
    }

    /**
     * Exp??dition des emails de confirmation et/ou de notification.
     *
     * @return void
     */
    public function emailSend()
    {
        if ($params = $this->parseMail($this->params('confirmation', []), 'confirmation')) {
            Mail::send($params);
        }

        if ($params = $this->parseMail($this->params('notification', []), 'notification')) {
            Mail::send($params);
        }
    }

    /**
     * Traitement des attributs de configuration de l'email.
     *
     * @param array $params Liste des param??tres d'envoi.
     * @param string $type Type d'exp??dition de l'email. notification|confirmation.
     *
     * @return array
     */
    public function parseMail($params, $type)
    {
        if ($params === false) {
            return [];
        }

        $params['subject'] = $params['subject']
            ?? sprintf(__('%1$s - Demande de contact', 'tify'), get_bloginfo('name'));

        $params['to'] = $params['to'] ?? get_option('admin_email');

        $params = array_map([$this->form(), 'fieldTagValue'], $params);

        $fields = $this->form()->fields()->collect()->filter(function (FactoryField $item) {
            return $item->getAddonOption('mailer', 'show') && $item->supports('request');
        });

        $fields->each(function (FactoryField $item) {
            $mailer_label = $item->getAddonOption('mailer', 'label');
            $item['mailer_label'] = $mailer_label instanceof Closure
                ? call_user_func($mailer_label, $item)
                : $mailer_label;

            $mailer_value = $item->getAddonOption('mailer', 'value');
            $item['mailer_value'] = $mailer_value instanceof Closure
                ? call_user_func($mailer_value, $item)
                : $mailer_value;
        });

        $params['content'] = $params['content'] ?? [];

        $params['content']['body'] = $params['content']['body']
            ?? (string)$this->form()->viewer('addon/mailer/body', array_merge($params, compact('fields')));

        return $params;
    }
}