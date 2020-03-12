<?php declare(strict_types=1);

namespace tiFy\Form\Addon\User;

use tiFy\Contracts\Form\FactoryField;
use tiFy\Form\AddonFactory;
use tiFy\Validation\Validator as v;
use WP_Error;
use WP_User;

class User extends AddonFactory
{
    /**
     * Liste des attributs de configuration.
     * @var array
     */
    protected $attributes = [
        'user_id'                    => 0,
        'roles'                      => ['subscriber'],
        'send_password_change_email' => false,
        'send_email_change_email'    => false,
        'auto_auth'                  => false,
    ];

    /**
     * Utilisateur courant.
     * @var WP_User|null
     */
    protected $user;

    /**
     * Liste des clés de données utilisateurs permises.
     * @var string[]
     */
    protected $userdataKeys = [
        'user_login',
        'role',
        'first_name',
        'last_name',
        'nickname',
        'display_name',
        'user_email',
        'user_url',
        'description',
        'user_pass',
        'show_admin_bar_front',
        'meta',
        'option',
    ];

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        $this->form()->events()->listen('field.prepared', function (FactoryField $field) {
            if ($field->getAddonOption($this->name(), 'userdata') === 'user_pass') {
                if (!$field->has('attrs.onpaste')) {
                    $field->set('attrs.onpaste', 'off');
                }
                if (!$field->has('attrs.autocomplete')) {
                    $field->set('attrs.autocomplete', 'new-password');
                }
            }
        });

        $this->form()->events()
            ->listen('request.validation.field', function (FactoryField $field) {
                $this->form()->events('addon.user.field.validation', [&$field]);
            })
            ->listen('request.submit', function () {
                $this->form()->events('addon.user.save');
            })
            ->listen('addon.user.field.validation', [$this, 'fieldValidation'])
            ->listen('addon.user.save', [$this, 'save']);
    }

    /**
     * Vérification de permission d'un rôle.
     *
     * @param string $name Nom de qualification du rôle.
     *
     * @return bool
     */
    public function canRole($name)
    {
        return get_role($name) && in_array($name, $this->params('roles', []));
    }

    /**
     * @inheritDoc
     */
    public function defaultsFieldOptions(): array
    {
        return [
            'userdata' => false,
        ];
    }

    /**
     * Récupération de l'identifiant de l'utilisateur concerné par le formulaire.
     *
     * @return WP_User
     */
    public function getUser(): WP_User
    {
        return $this->user;
    }

    /**
     * Récupération de la liste des rôles.
     *
     * @return array
     */
    public function getRoles(): array
    {
        return $this->params('roles', []);
    }

    /**
     * Vérifie si l'utilisateur courant édite son profile.
     *
     * @return boolean
     */
    public function isProfile()
    {
        return ($user_id = $this->getUser()->ID) && ($user_id === get_current_user_id());
    }

    /**
     * Vérifie si une clé correspond à un clé de données utilisateurs principales.
     *
     * @param string $key
     *
     * @return boolean
     */
    public function isUserdataKey(string $key): bool
    {
        return in_array($key, $this->userdataKeys);
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): void
    {
        $this->params(['roles' => (array)$this->params('roles', [])]);

        $this->user = ($user_id = $this->params('user_id')) ? new WP_User($user_id) : wp_get_current_user();
    }

    /**
     * Initialisation de l'utilisateur courant.
     *
     * @param int|WP_User $user Utilisateur.
     *
     * @return $this
     */
    public function setUser($user): self
    {
        $this->user = new WP_User($user);

        return $this;
    }

    /**
     * Vérification d'intégrité d'un champ.
     *
     * @param FactoryField $field Instance du champ.
     *
     * @return void
     */
    public function fieldValidation(FactoryField &$field): void
    {
        if (!$userdata = $field->getAddonOption($this->name(), 'userdata', false)) {
            return;
        } else {
            if (!in_array($userdata, ['user_login', 'user_email', 'role'])) {
                return;
            } else {
                switch ($userdata) {
                    // Identifiant de connexion
                    case 'user_login' :
                        if (!$this->isProfile() && get_user_by('login', $field->getValue())) {
                            $field->addError(
                                __('Cet identifiant est déjà utilisé par un autre utilisateur.', 'tify')
                            );
                        }
                        if (is_multisite()) {
                            // Lettres et/ou chiffres uniquement
                            $user_name = $field->getValue();
                            $orig_username = $user_name;
                            $user_name = preg_replace('/\s+/', '', sanitize_user($user_name, true));

                            if ($user_name != $orig_username || preg_match('/[^a-z0-9]/', $user_name)) {
                                $field->addError(
                                    __('L\'identifiant de connexion ne devrait contenir que des lettres minuscules (a-z)' .
                                        ' et des chiffres.',
                                        'tify'
                                    )
                                );
                            }

                            // Identifiant réservés
                            $illegal_names = get_site_option('illegal_names');
                            if (!is_array($illegal_names)) {
                                $illegal_names = ['www', 'web', 'root', 'admin', 'main', 'invite', 'administrator'];
                                add_site_option('illegal_names', $illegal_names);
                            }

                            if (in_array($user_name, $illegal_names)) {
                                $field->addError(
                                    __('Désolé, cet identifiant de connexion n\'est pas permis.', 'tify')
                                );
                            }

                            // Identifiant réservés personnalisés
                            $illegal_logins = (array)apply_filters('illegal_user_logins', []);
                            if (in_array(strtolower($user_name), array_map('strtolower', $illegal_logins))) {
                                $field->addError(
                                    __('Désolé, cet identifiant de connexion n\'est pas permis.', 'tify')
                                );
                            }

                            // Longueur minimale
                            if (strlen($user_name) < 4) {
                                $field->addError(
                                    __('L\'identifiant de connexion doit contenir au moins 4 caractères.', 'tify')
                                );
                            }

                            // Longueur maximale
                            if (strlen($user_name) > 60) {
                                $field->addError(
                                    __(
                                        'L\'identifiant de connexion ne doit pas contenir plus de 60 caractères.',
                                        'tify'
                                    )
                                );
                            }

                            // Lettres obligatoire
                            if (preg_match('/^[0-9]*$/', $user_name)) {
                                $field->addError(
                                    __('L\'identifiant de connexion doit contenir des lettres.', 'tify')
                                );
                            }
                        }
                        break;

                    // Email
                    case 'user_email' :
                        $value = $field->getValue();

                        if (!v::notEmpty()->validate($value )) {
                            $field->addError(
                                __('Veuillez saisir une adresse de messagerie.', 'tify')
                            );
                        } elseif (!v::email()->validate($value)) {
                            $field->addError(
                                __('L\'adresse de messagerie est incorrecte.', 'tify')
                            );
                        } elseif (
                            get_user_by('email', $value) &&
                            (!$this->isProfile() || ($value !== $this->getUser()->user_email))
                        ) {
                            $field->addError(
                                __('Cet email est déjà utilisé par un autre utilisateur.', 'tify')
                            );
                        }
                        break;

                    // Rôle
                    case 'role' :
                        if (!$this->canRole($field->getValue())) {
                            $field->addError(
                                __('L\'attribution de ce rôle n\'est pas permise.', 'tify')
                            );
                        }
                        break;
                }
            }
        }
    }

    /**
     * Sauvegarde de l'utilisateur.
     *
     * @return void
     */
    public function save(): void
    {
        $userdatas = [];

        foreach ($this->form()->fields() as $slug => $field) {
            if (!$key = $field->getAddonOption($this->name(), 'userdata', false)) {
                continue;
            } elseif (!$this->isUserdataKey($key)) {
                continue;
            } elseif (in_array($key, ['meta', 'option'])) {
                continue;
            }

            $userdatas[$key] = $this->form()->request()->get($slug);
        }

        if (!isset($userdatas['user_login'])) {
            $userdatas['user_login'] = md5(wp_generate_password(20). uniqid());
        }

        if (isset($userdatas['show_admin_bar_front'])) {
            $userdatas['show_admin_bar_front'] = filter_var($userdatas['show_admin_bar_front'], FILTER_VALIDATE_BOOLEAN)
                ? ''
                : 'false';
        }

        if ($this->isProfile()) {
            $userdatas['ID'] = $this->getUser()->ID;

            if (empty($userdatas['user_pass'])) {
                unset($userdatas['user_pass']);
            }

            if (empty($userdatas['role'])) {
                unset($userdatas['role']);
            }

            add_filter('send_password_change_email', function () {
                return $this->params('send_password_change_email', false);
            });

            add_filter('send_email_change_email', function () {
                return $this->params('send_email_change_email', false);
            });

            $result = wp_update_user($userdatas);
        } else {
            if (!isset($userdatas['user_pass'])) {
                $userdatas['user_pass'] = '';
            }

            if (empty($userdatas['role'])) {
                $userdatas['role'] = ($roles = $this->getRoles())
                    ? current($roles)
                    : get_option('default_role', 'subscriber');
            }

            if (is_multisite()) {
                $validate = wpmu_validate_user_signup($userdatas['user_login'], $userdatas['user_email']);
                $wp_error = $validate['errors'] ?? null;

                if (($wp_error instanceof WP_Error) && !empty($wp_error->errors)) {
                    $this->form()->notices()->add('error', $wp_error->get_error_message());

                    return;
                }
            }

            $result = wp_insert_user($userdatas);
        }

        if (is_wp_error($result)) {
            $this->form()->notices()->add('error', $result->get_error_message());
        } else {
            $this->setUser($result);

            foreach ($this->form()->fields() as $field) {
                if ($key = $field->getAddonOption($this->name(), 'userdata', false)) {
                    switch ($key) {
                        case 'meta' :
                            update_user_meta($this->getUser()->ID, $field->getName(), $field->getValue());
                            break;
                        case 'option' :
                            update_user_option($this->getUser()->ID, $field->getName(), $field->getValue());
                            break;
                    }
                }
            }

            $this->form()->events('addon.user.success', [$this->getUser(), $this]);

            // Authentification automatique.
            if ($auto_auth = $this->params('auto_auth')) {
                wp_clear_auth_cookie();
                wp_set_auth_cookie((int)$this->getUser()->ID);
            }
        }
    }
}