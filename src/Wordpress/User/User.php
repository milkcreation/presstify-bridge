<?php

namespace tiFy\Wordpress\User;

use Illuminate\Support\Collection;
use tiFy\Contracts\User\RoleFactory;
use tiFy\Contracts\User\SigninFactory as BaseSigninFactoryContract;
use tiFy\Contracts\User\User as tiFyUser;
use tiFy\Wordpress\Contracts\User as UserContract;
use tiFy\Wordpress\User\Signin\SigninFactory;
use WP_Roles;
use WP_User_Query;

class User implements UserContract
{
    /**
     * Instance de l'accesseur de service utilisateur.
     * @var tiFyUser
     */
    protected $accessor;

    /**
     * CONSTRUCTEUR
     *
     * @param tiFyUser $accessor Instance du gestionnaire utilisateur.
     *
     * @return void
     */
    public function __construct(tiFyUser $accessor)
    {
        $this->accessor = $accessor;

        add_action('init', function () {
            /* @see https://codex.wordpress.org/Roles_and_Capabilities */
            foreach (config('user.role', []) as $name => $attrs) {
                $this->accessor->role()->register($name, $attrs);
            }
        }, 0);

        add_action('init', function () {
            global $wp_roles;

            foreach($wp_roles->roles as $role => $data) {
                if (!$this->accessor->role()->get($role)) {
                    $this->accessor->role()->register(
                        $role, ['display_name' => $data['name'], 'capabilities' => $data['capabilities']]
                    );
                }
            }

            foreach (config('user.signin', []) as $name => $attrs) {
                $this->accessor->signin()->register($name, $attrs);
            }
            foreach (config('user.signup', []) as $name => $attrs) {
                $this->accessor->signup()->register($name, $attrs);
            }
        }, 999998);

        add_action('profile_update', function ($user_id) {
            $this->accessor->meta()->Save($user_id);
            $this->accessor->option()->Save($user_id);
        }, 2);

        add_action('user_register', function ($user_id) {
            $this->accessor->meta()->Save($user_id);
            $this->accessor->option()->Save($user_id);
        });

        events()->on('user.role.factory.boot', function (RoleFactory $factory) {
            /* @var WP_Roles $wp_roles */
            global $wp_roles;

            $name = $factory->getName();

            /** @var \WP_Role $role */
            if (!$role = $wp_roles->get_role($name)) {
                $role = $wp_roles->add_role($name, $factory->get('display_name'));
            } elseif (($names = $wp_roles->get_names()) && ($names[$name] !== $factory->get('display_name'))) {
                $wp_roles->remove_role($name);
                $role = $wp_roles->add_role($name, $factory->get('display_name'));
            }

            foreach ($factory->get('capabilities', []) as $cap => $grant) {
                if (!isset($role->capabilities[$cap]) || ($role->capabilities[$cap] !== $grant)) {
                    $role->add_cap($cap, $grant);
                }
            }
        });

        $this->register();
    }

    /**
     * Déclaration des surchages de service du conteneur d'injection.
     *
     * @return void
     */
    public function register()
    {
        app()->add(BaseSigninFactoryContract::class, function () {
            return new SigninFactory();
        });
    }

    /**
     * @inheritdoc
     */
    public function pluck($value = 'display_name', $key = 'ID', $query_args = [])
    {
        $users = [];
        $query_args['fields'] = [$key, $value];

        $user_query = new WP_User_Query($query_args);

        if (empty($user_query->get_results())) {
            return $users;
        }
        return (new Collection($user_query->get_results()))->pluck($value, $key)->all();
    }

    /**
     * @inheritdoc
     */
    public function roleDisplayName($role)
    {
        $wp_roles = new WP_Roles();
        $roles = $wp_roles->get_names();

        if (!isset($roles[$role])) {
            return $role;
        }
        return translate_user_role($roles[$role]);
    }
}