<?php declare(strict_types=1);

namespace tiFy\Auth\Signup;

use tiFy\Contracts\Auth\{Auth, Signup as SignupContract};
use tiFy\Contracts\Form\FormFactory;
use tiFy\Support\{ParamsBag, Proxy\Form};

class Signup extends ParamsBag implements SignupContract
{
    /**
     * Instance du formulaire.
     * @var FormFactory
     */
    protected $form;

    /**
     * Instance du gestionnaire d'interface d'authentification.
     * @var Auth
     */
    protected $manager;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Indicateur d'initialisation de la classe.
     * @var boolean
     */
    protected $prepared = false;

    /**
     * CONSTRUCTEUR.
     *
     * @param Auth $manager Instance du gestionnaire d'authentification.
     *
     * @return void
     */
    public function __construct(Auth $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->form();
    }

    /**
     * @inheritDoc
     */
    public function boot(): void { }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'fields' => [
                'login'    => [
                    'addons'   => [
                        'user' => [
                            'userdata' => 'user_login',
                        ],
                    ],
                    'label'    => __('Identifiant', 'tify'),
                    'required' => true,
                    'type'     => 'text',
                ],
                'email'    => [
                    'addons'      => [
                        'user' => [
                            'userdata' => 'user_email',
                        ],
                    ],
                    'attrs'       => [
                        'placeholder' => __('Indiquez votre e-mail', 'tify'),
                    ],
                    'label'       => __('Adresse e-mail', 'tify'),
                    'required'    => true,
                    'type'        => 'text',
                    'validations' => 'email',
                ],
                'password' => [
                    'addons'      => [
                        'user' => [
                            'userdata' => 'user_pass',
                        ],
                    ],
                    'attrs'       => [
                        'placeholder' => __('Renseignez un mot de passe', 'tify'),
                    ],
                    'label'       => __('Mot de passe', 'tify'),
                    'required'    => true,
                    'type'        => 'password',
                    'validations' => 'valid-password',
                ],
                'confirm'  => [
                    'attrs'       => [
                        'placeholder' => __('Confirmez votre mot de passe', 'tify'),
                    ],
                    'label'       => __('Confirmation de mot de passe', 'tify'),
                    'required'    => true,
                    'type'        => 'password',
                    'validations' => [
                        'call'    => 'compare',
                        'args'    => ['password'],
                        'message' => __(
                            'Les champs "Mot de passe" et "Confirmation de mot de passe" doivent correspondre.',
                            'tify'
                        ),
                    ]
                ],
                'captcha'  => [
                    'label'    => __('Code de sécurité', 'tify'),
                    'required' => true,
                    'type'     => 'captcha',
                ],
            ],
            'addons' => ['user' => true],
        ];
    }

    /**
     * @inheritDoc
     */
    public function form(): FormFactory
    {
        return $this->form;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     *
     * @return SignupContract
     */
    public function parse(): SignupContract
    {
        parent::parse();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $name, array $attrs = []): SignupContract
    {
        if (!$this->prepared) {
            $this->name = $name;

            $this->boot();

            $this->set($attrs)->parse();

            Form::set($this->getName(), $this->all());

            $this->form = Form::get($this->getName());

            $this->prepared = true;
        }

        return $this;
    }
}