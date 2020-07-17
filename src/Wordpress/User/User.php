<?php declare(strict_types=1);

namespace tiFy\Wordpress\User;

use Illuminate\Support\Collection;
use tiFy\Contracts\User\RoleFactory;
use tiFy\Contracts\User\User as UserManager;
use tiFy\Wordpress\Contracts\User as UserContract;
use WP_Roles;
use WP_User_Query;
use WP_Role;

class User implements UserContract
{
    /**
     * Instance du gestionnaire des utilisateurs.
     * @var UserManager
     */
    protected $manager;

    /**
     * CONSTRUCTEUR
     *
     * @param UserManager $manager
     *
     * @return void
     */
    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;

        add_action('init', function () {
            foreach (config('user.role', []) as $name => $attrs) {
                $this->manager->role()->register($name, $attrs);
            }
        }, 0);

        add_action('init', function () {
            global $wp_roles;

            foreach ($wp_roles->roles as $role => $data) {
                if (!$this->manager->role()->get($role)) {
                    $this->manager->role()->register(
                        $role, ['display_name' => $data['name'], 'capabilities' => $data['capabilities']]
                    );
                }
            }
        }, 999998);

        add_action('profile_update', function (int $user_id) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            } elseif (defined('DOING_AJAX') && DOING_AJAX) {
                return;
            }

            $this->manager->meta()->save($user_id);
            $this->manager->option()->Save($user_id);
        });

        add_action('user_register', function (int $user_id) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            } elseif (defined('DOING_AJAX') && DOING_AJAX) {
                return;
            }

            $this->manager->meta()->save($user_id);
            $this->manager->option()->Save($user_id);
        });

        events()->on('user.role.factory.boot', function (RoleFactory $factory) {
            /* @var WP_Roles $wp_roles */
            global $wp_roles;

            $name = $factory->getName();

            /** @var WP_Role $role */
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
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
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