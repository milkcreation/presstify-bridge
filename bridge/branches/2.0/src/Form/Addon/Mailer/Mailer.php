<?php

namespace tiFy\Form\Addon\Mailer;

use tiFy\Contracts\Form\FactoryField;
use tiFy\Contracts\Form\FactoryRequest;
use tiFy\Contracts\Mail\Mailer as MailerContract;
use tiFy\Contracts\Metabox\MetaboxManager;
use tiFy\Form\AddonController;

class Mailer extends AddonController
{
    /**
     * Définition des options de formulaire par défaut
     *
     * @var array {
     * @var bool|array $admin {
     *      Affichage de l'intitulé et de la valeur de saisie du champ dans le corps du mail
     *
     * @var bool $confirmation Activation de l'interface d'administration de l'email de confirmation de
     *      reception à destination des utilisateurs.
     * @var bool $notification Activation de l'interface d'administration de l'email de notification à
     *      destination des administrateurs de site
     * }
     *
     * @var bool|array $confirmation Attributs de configuration d'expédition de l'email de confirmation de
     *      reception à destination des utilisateurs.
     *
     * @var bool|array $notification Attributs de configuration d'expédition de l'email de notification à
     *      destination des administrateurs de site.
     *
     * @var string $option_name_prefix Prefixe du nom d'enregistrement des options d'expédition de mail (usage
     *      avancé).
     * @var array $option_names (usage avancé) Cartographie nom d'enregistrement des options en base.
     *
     * @var bool|string $debug Affichage du mail au lieu de l'expédition
     *      (false|'confirmation'|'notification')
     * }
     */
    public $attributes = [
        'admin'              => [
            'confirmation' => true,
            'notification' => true,
        ],
        'confirmation'       => [],
        'notification'       => [],
        'option_name_prefix' => '',
        'option_names'       => [],
        'debug'              => false,
    ];

    /**
     * @inheritdoc
     */
    public function boot()
    {
        $this->events()
             ->listen('request.submit', [$this, 'onRequestSubmit'])
             ->listen('request.success', [$this, 'onRequestSuccess']);

        $prefix       = $this->get('option_name_prefix', "FormMailer_{$this->form()->name()}");
        $option_names = $this->get('option_names', []);
        foreach (['confirmation', 'sender', 'notification', 'recipients'] as $option) {
            $option_names[$option] = $option_names[$option] ?? "{$prefix}{$option}";
        }
        $this->set('option_names', $option_names);

        if ($this->get('confirmation') && get_option($option_names['confirmation'])) :
            $from = get_option($option_names['sender']) ? ! '' : ['email' => '', 'name' => ''];

            $this->set('confirmation.from', [$from['email'], $from['name']]);
        endif;

        if (
            $this->get('notification') &&
            get_option($option_names['notification']) &&
            ($to = get_option($option_names['recipients']))
        ) :
            array_walk($to, function (&$item) {
                $item = [$item['email'], $item['name']];
            });

            $this->set('notification.to', $to);
        endif;

        /** @var MetaboxManager $metabox */
        if ($this->get('admin.confirmation') || $this->get('admin.notification')) :
            $metabox = app('metabox');

            $metabox->add("FormAddonMailer-{$this->form()->name()}", 'tify_options@options', [
                'title' => $this->form()->getTitle(),
            ]);

            if ($this->get('admin.confirmation')) :
                $metabox->add(
                    "FormAddonMailerConfirmation-{$this->form()->name()}",
                    'tify_options@options',
                    [
                        'parent'   => "FormAddonMailer-{$this->form()->name()}",
                        'content'  => $this->resolve('addon.mailer.options-confirmation', [$this->form()]),
                        'position' => 1,
                    ]
                );
            endif;

            if ($this->get('admin.notification')) :
                $metabox->add(
                    "FormAddonMailerNotification-{$this->form()->name()}",
                    'tify_options@options',
                    [
                        'parent'   => "FormAddonMailer-{$this->form()->name()}",
                        'content'  => $this->resolve('addon.mailer.options-notification', [$this->form()]),
                        'position' => 2,
                    ]
                );
            endif;
        endif;
    }

    /**
     * @inheritdoc
     */
    public function defaults()
    {
        return [
            'notification' => [
                'subject' => sprintf(
                    __('Vous avez une nouvelle demande de contact sur le site %s', 'tify'),
                    get_bloginfo('name')
                ),
            ],
            'confirmation' => [
                'subject' => sprintf(
                    __('Votre demande de contact sur le site %s', 'tify'),
                    get_bloginfo('name')
                ),
                'to'      => '%%email%%',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function defaultsFieldOptions()
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
     * Court-circuitage du traitement de la requête du formulaire.
     *
     * @param FactoryRequest $request Instance du contrôleur de traitement de la requête de soumission associée au
     *                                formulaire.
     *
     * @return void
     */
    public function onRequestSubmit(FactoryRequest $request)
    {
        /** @var MailerContract $mailer */
        $mailer = app('mailer');

        if ($debug = $this->get('debug')) :
            switch ($debug) :
                default:
                case 'confirmation' :
                    if ($params = $this->parseParams($this->get('confirmation', []), 'confirmation')) :
                        $mailer->debug($params);
                    else :
                        wp_die(
                            __('Email de confirmation non configuré.', 'tify'),
                            __('FormAddonMailer - Erreur', 'tify'),
                            500
                        );
                    endif;
                    break;

                case 'notification' :
                    if ($params = $this->parseParams($this->get('notification', []), 'notification')) :
                        $mailer->debug($params);
                    else :
                        wp_die(
                            __('Email de notification non configuré.', 'tify'),
                            __('FormAddonMailer - Erreur', 'tify'),
                            500
                        );
                    endif;
                    break;
            endswitch;
        endif;
    }

    /**
     * Court-circuitage de l'issue d'un traitement de formulaire réussi.
     *
     * @param FactoryRequest $request Instance du contrôleur de traitement de la requête de soumission associée au
     *                                formulaire.
     *
     * @return void
     */
    public function onRequestSuccess(FactoryRequest $request)
    {
        /** @var MailerContract $mailer */
        $mailer = app('mailer');

        if ($params = $this->parseParams($this->get('confirmation', []), 'confirmation')) {
            $mailer->send($params);
        }

        if ($params = $this->parseParams($this->get('notification', []), 'notification')) {
            $mailer->send($params);
        }
    }

    /**
     * Traitement des attributs de configuration de l'email.
     *
     * @param array $params Liste des paramètres d'envoi.
     * @param string $type Type d'expédition de l'email. notification|confirmation.
     *
     * @return array
     */
    public function parseParams($params, $type)
    {
        if ($params === false) :
            return [];
        endif;

        $params['subject'] = $params['subject']
                             ?? sprintf(__('%1$s - Demande de contact', 'tify'), get_bloginfo('name'));

        $params['to'] = $params['to']
                        ?? get_option('admin_email');

        $params = array_map([$this, 'fieldTagValue'], $params);

        $fields = $this->fields()->collect()->filter(function (FactoryField $item) {
            return $item->getAddonOption('mailer', 'show') && $item->supports('request');
        });

        $fields->each(function (FactoryField $item) {
            $mailer_label         = $item->getAddonOption('mailer', 'label');
            $item['mailer_label'] = $mailer_label instanceof \Closure
                ? call_user_func($mailer_label, $item)
                : $mailer_label;

            $mailer_value         = $item->getAddonOption('mailer', 'value');
            $item['mailer_value'] = $mailer_value instanceof \Closure
                ? call_user_func($mailer_value, $item)
                : $mailer_value;
        });

        $params['body'] = $params['body']
                          ?? (string)$this->viewer('addon/mailer/body', array_merge($params, compact('fields')));

        return $params;
    }
}