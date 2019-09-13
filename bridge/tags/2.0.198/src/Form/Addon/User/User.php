<?php

namespace tiFy\Form\Addon\User;

use Illuminate\Support\Arr;
use tiFy\Contracts\Form\FactoryField;
use tiFy\Contracts\Form\FactoryRequest;
use tiFy\Form\AddonController;
use WP_User;

class User extends AddonController
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
        'auto_auth'                  => false
    ];

    /**
     * Utilisateur courant.
     * @var WP_User
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
        'option'
    ];

    /**
     * @inheritdoc
     */
    public function boot()
    {
        $this->events()->listen('field.prepared', function (FactoryField $field) {
            if ($field->getAddonOption($this->getName(), 'userdata') === 'user_pass') {
                if ( ! $field->has('attrs.onpaste')) {
                    $field->set('attrs.onpaste', 'off');
                }
                if ( ! $field->has('attrs.autocomplete')) {
                    $field->set('attrs.autocomplete', 'new-password');
                }
            }
        });

        $this->events()->listen('request.validation.field', [$this, 'onRequestValidationFields']);
        $this->events()->listen('request.submit', [$this, 'onRequestSubmit']);
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
        return get_role($name) && in_array($name, $this->get('roles', []));
    }

    /**
     * @inheritdoc
     */
    public function defaultsFieldOptions()
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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Récupération de la liste de qualification .
     *
     * @return WP_User
     */
    public function getRoles()
    {
        return $this->get('roles', []);
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
    public function isUserdataKey($key)
    {
        return in_array($key, $this->userdataKeys);
    }

    /**
     * @inheritdoc
     */
    public function parse($attrs = [])
    {
        parent::parse($attrs);

        $this->set('roles', Arr::wrap($this->get('roles', [])));

        $this->user = ($user_id = $this->get('user_id'))
            ? new WP_User($user_id)
            : wp_get_current_user();
    }

    /**
     * Initialisation de l'utilisateur courant.
     *
     * @param int|WP_User $user Utilisateur.
     *
     * @return WP_User
     */
    public function setUser($user)
    {
        return $this->user = new WP_User($user);
    }

    /**
     * Vérification d'intégrité d'un champ.
     *
     * @param FactoryField $field Instance du contrôleur de champ.
     *
     * @return void
     */
    public function onRequestValidationFields(FactoryField &$field)
    {
        if (!$userdata = $field->getAddonOption($this->getName(), 'userdata', false)) :
            return;
        endif;

        if (!in_array($userdata, ['user_login', 'user_email', 'role'])) :
            return;
        endif;

        switch ($userdata) :
            // Identifiant de connexion
            case 'user_login' :
                if (!$this->isProfile() && get_user_by('login', $field->getValue())) :
                    $field->notices()->add(
                        'error',
                        __('Cet identifiant est déjà utilisé par un autre utilisateur.', 'tify'),
                        ['field' => $field->getSlug()]
                    );
                endif;

                if (is_multisite()) :
                    // Lettres et/ou chiffres uniquement
                    $user_name = $field->getValue();
                    $orig_username = $user_name;
                    $user_name = preg_replace('/\s+/', '', sanitize_user($user_name, true));
                    if ($user_name != $orig_username || preg_match('/[^a-z0-9]/', $user_name)) :
                        $field->notices()->add(
                            'error',
                            __('L\'identifiant de connexion ne devrait contenir que des lettres minuscules (a-z)' .
                                ' et des chiffres.',
                                'tify'
                            ),
                            ['field' => $field->getSlug()]
                        );
                    endif;

                    // Identifiant réservés
                    $illegal_names = get_site_option('illegal_names');
                    if (!is_array($illegal_names)) :
                        $illegal_names = ['www', 'web', 'root', 'admin', 'main', 'invite', 'administrator'];
                        add_site_option('illegal_names', $illegal_names);
                    endif;

                    if (in_array($user_name, $illegal_names)) :
                        $field->notices()->add(
                            'error',
                            __('Désolé, cet identifiant de connexion n\'est pas permis.', 'tify'),
                            ['field' => $field->getSlug()]
                        );
                    endif;

                    // Identifiant réservés personnalisés
                    $illegal_logins = (array)apply_filters('illegal_user_logins', []);
                    if (in_array(strtolower($user_name), array_map('strtolower', $illegal_logins))) :
                        $field->notices()->add(
                            'error',
                            __('Désolé, cet identifiant de connexion n\'est pas permis.', 'tify'),
                            ['field' => $field->getSlug()]
                        );
                    endif;

                    // Longueur minimale
                    if (strlen($user_name) < 4) :
                        $field->notices()->add(
                            'error',
                            __('L\'identifiant de connexion doit contenir au moins 4 caractères.', 'tify'),
                            ['field' => $field->getSlug()]
                        );
                    endif;

                    // Longueur maximale
                    if (strlen($user_name) > 60) :
                        $field->notices()->add(
                            'error',
                            __('L\'identifiant de connexion ne doit pas contenir plus de 60 caractères.', 'tify'),
                            ['field' => $field->getSlug()]
                        );
                    endif;

                    // Lettres obligatoire
                    if (preg_match('/^[0-9]*$/', $user_name)) :
                        $field->notices()->add(
                            'error',
                            __('L\'identifiant de connexion doit contenir des lettres.', 'tify'),
                            ['field' => $field->getSlug()]
                        );
                    endif;
                endif;
                break;

            // Email
            case 'user_email' :
                if (get_user_by('email', $field->getValue())) :
                    if (!$this->isProfile() || ($field->getValue() !== $this->getUser()->user_email)) :
                        $field->notices()->add(
                            'error',
                            __('Cet email est déjà utilisé par un autre utilisateur.', 'tify'),
                            ['field' => $field->getSlug()]
                        );
                    endif;
                endif;
                break;

            // Rôle
            case 'role' :
                if (!$this->canRole($field->getValue())) :
                    $field->notices()->add(
                        'error',
                        __('L\'attribution de ce rôle n\'est pas permise.', 'tify'),
                        ['field' => $field->getSlug()]
                    );
                endif;
                break;
        endswitch;
    }

    /**
     * Court-circuitage du traitement de la requête du formulaire.
     *
     * @param FactoryRequest $request Instance du contrôleur de traitement de la requête de soumission du formulaire
     *     associé.
     *
     * @return void
     */
    public function onRequestSubmit(FactoryRequest $request)
    {
        $userdatas = [];

        foreach ($this->fields() as $field) :
            if (!$key = $field->getAddonOption($this->getName(), 'userdata', false)) :
                continue;
            endif;
            if (!$this->isUserdataKey($key)) :
                continue;
            endif;
            if (in_array($key, ['meta', 'option'])) :
                continue;
            endif;

            $userdatas[$key] = $request->get($field->getName());
        endforeach;

        if (isset($userdatas['show_admin_bar_front'])) :
            $userdatas['show_admin_bar_front'] = filter_var($userdatas['show_admin_bar_front'], FILTER_VALIDATE_BOOLEAN)
                ? ''
                : 'false';
        endif;

        if ($this->isProfile()) :
            $userdatas['ID'] = $this->getUser()->ID;

            if (empty($userdatas['user_pass'])) :
                unset($userdatas['user_pass']);
            endif;

            if (empty($userdatas['role'])) :
                unset($userdatas['role']);
            endif;

            add_filter('send_password_change_email', function () {
                return $this->get('send_password_change_email', false);
            });

            add_filter('send_email_change_email', function () {
                return $this->get('send_email_change_email', false);
            });

            $result = wp_update_user($userdatas);

        // Création
        else :
            if (empty($userdatas['role'])) :
                $userdatas['role'] = ($roles = $this->getRoles())
                    ? current($roles)
                    : get_option('default_role', 'subscriber');
            endif;

            if (is_multisite()) :
                $validate = wpmu_validate_user_signup($userdatas['user_login'], $userdatas['user_email']);
                $wp_error = $validate['errors'] ?? null;

                if (is_wp_error($wp_error) && !empty($wp_error->errors)) :
                    $request->notices()->add('error', $wp_error->get_error_message());
                    return;
                endif;
            endif;

            $result = wp_insert_user($userdatas);
        endif;

        if (is_wp_error($result)) :
            $request->notices()->add('error', $result->get_error_message());
        else :
            $user = $this->setUser($result);

            foreach ($this->fields() as $field) :
                if (!$key = $field->getAddonOption($this->getName(), 'userdata', false)) :
                    continue;
                endif;

                switch($key) :
                    case 'meta' :
                        update_user_meta($user->ID, $field->getName(), $field->getValue());
                        break;
                    case 'option' :
                        update_user_option($user->ID, $field->getName(), $field->getValue());
                        break;
                endswitch;
            endforeach;

            $this->events('addon.user.success', [$user, $this]);

            // Authentification automatique.
            if ($auto_auth = $this->get('auto_auth')) {
                wp_clear_auth_cookie();
                wp_set_auth_cookie((int) $user->ID);
            }
        endif;
    }
}