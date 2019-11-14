<?php

namespace tiFy\User\Signup;

use tiFy\Contracts\Form\FormFactory;
use tiFy\Contracts\User\SignupFactory as SignupFactoryContract;
use tiFy\Support\ParamsBag;

class SignupFactory extends ParamsBag implements SignupFactoryContract
{
    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * CONSTRUCTEUR.
     *
     * @param string $name Nom de qualification.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return void
     */
    public function __construct(string $name, array $attrs)
    {
        $this->name = $name;

        $this->set($attrs)->parse();

        form()->register($this->getName(), $this->all());
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return (string)$this->form();
    }

    /**
     * @inheritdoc
     */
    public function defaults()
    {
        return [
            'fields' => [
                'login'    => [
                    'label'    => __('Identifiant', 'tify'),
                    'type'     => 'text',
                    'required' => true,
                    'addons'   => [
                        'user' => [
                            'userdata' => 'user_login'
                        ]
                    ]
                ],
                'email'    => [
                    'label'       => __('Adresse e-mail', 'tify'),
                    'attrs'       => [
                        'placeholder' => __('Indiquez votre e-mail', 'tify'),
                    ],
                    'type'        => 'text',
                    'validations' => 'email',
                    'required'    => true,
                    'addons'      => [
                        'user' => [
                            'userdata' => 'user_email'
                        ]
                    ]
                ],
                'password' => [
                    'label'       => __('Mot de passe', 'tify'),
                    'attrs'       => [
                        'placeholder' => __('Renseignez un mot de passe', 'tify'),
                    ],
                    'type'        => 'password',
                    'validations' => 'valid-password',
                    'required'    => true,
                    'addons'      => [
                        'user' => [
                            'userdata' => 'user_pass'
                        ]
                    ]
                ],
                'confirm'  => [
                    'label'       => __('Confirmation de mot de passe', 'tify'),
                    'attrs'       => [
                        'placeholder' => __('Confirmez votre mot de passe', 'tify'),
                    ],
                    'type'        => 'password',
                    'validations' => [
                        'call'    => 'compare',
                        'args'    => ['password'],
                        'message' => __(
                            'Les champs "Mot de passe" et "Confirmation de mot de passe" doivent correspondre.',
                            'tify'
                        )
                    ],
                    'required'    => true
                ],
                'captcha'  => [
                    'label'    => __('Code de sécurité', 'tify'),
                    'type'     => 'captcha',
                    'required' => true
                ]
            ],
            'addons' => ['user' => true]
        ];
    }

    /**
     * @inheritdoc
     */
    public function form(): ?FormFactory
    {
        return form()->get($this->getName());
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }
}