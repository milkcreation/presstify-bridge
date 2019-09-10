<?php declare(strict_types=1);

namespace tiFy\Wordpress\Query;

use tiFy\Contracts\User\RoleFactory;
use tiFy\Support\{ParamsBag, Proxy\Role};
use tiFy\Wordpress\Contracts\{Database\UserBuilder, QueryUser as QueryUserContract};
use tiFy\Wordpress\Database\Model\User as Model;
use WP_Site;
use WP_User;

class QueryUser extends ParamsBag implements QueryUserContract
{
    /**
     * Liste des sites pour lequels l'utilisateur est habilité.
     * @var WP_Site[]|array
     */
    protected $blogs;

    /**
     * Instance du modèle de base de données associé.
     * @var UserBuilder
     */
    protected $db;

    /**
     * Instance d'utilisateur Wordpress.
     * @var WP_User
     */
    protected $wp_user;

    /**
     * CONSTRUCTEUR
     *
     * @param WP_User $wp_user Instance d'utilisateur Wordpress.
     *
     * @return void
     */
    public function __construct(WP_User $wp_user)
    {
        $this->wp_user = $wp_user;

        $this->set($this->wp_user->to_array())->parse();
    }

    /**
     * @inheritDoc
     */
    public static function createFromGlobal(): QueryUserContract
    {
        return new static(wp_get_current_user());
    }

    /**
     * @inheritDoc
     */
    public static function createFromId(int $user_id): ?QueryUserContract
    {
        return (($wp_user = new WP_User($user_id)) && ($wp_user instanceof WP_User))
            ? new static($wp_user) : null;
    }

    /**
     * @inheritDoc
     */
    public static function createFromEmail(string $email): ?QueryUserContract
    {
        return (($userdata = WP_User::get_data_by('email', $email)) &&
            (($wp_user = new WP_User($userdata)) instanceof WP_User))
            ? new static($wp_user) : null;
    }

    /**
     * @inheritDoc
     */
    public function db(): UserBuilder
    {
        if (!$this->db) {
            $this->db = (new Model())->find($this->getId());
        }

        return $this->db;
    }

    /**
     * @inheritDoc
     */
    public function can(string $capability, ...$args): bool
    {
        return $this->getWpUser()->has_cap($capability, ...$args);
    }

    /**
     * @inheritDoc
     */
    public function capabilities(): array
    {
        return $this->getWpUser()->allcaps;
    }

    /**
     * @inheritDoc
     */
    public function getBlogs($all = false): iterable
    {
        if (is_null($this->blogs)) {
            $this->blogs = get_blogs_of_user($this->getId(), $all);

            array_walk($this->blogs, function (&$site) {
                $site = WP_Site::get_instance($site->userblog_id);
            });
        }

        return $this->blogs;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return $this->getWpUser()->description;
    }

    /**
     * @inheritDoc
     */
    public function getDisplayName(): string
    {
        return (string)$this->get('display_name', '');
    }

    /**
     * @inheritDoc
     */
    public function getEmail(): string
    {
        return (string)$this->get('user_email', '');
    }

    /**
     * @inheritDoc
     */
    public function getFirstName(): string
    {
        return $this->getWpUser()->first_name;
    }

    /**
     * @inheritDoc
     */
    public function getId(): int
    {
        return intval($this->get('ID', 0));
    }

    /**
     * @inheritDoc
     */
    public function getLastName(): string
    {
        return $this->getWpUser()->last_name;
    }

    /**
     * @inheritDoc
     */
    public function getLogin(): string
    {
        return $this->get('user_login', '');
    }

    /**
     * @inheritDoc
     */
    public function getNicename(): string
    {
        return $this->get('user_nicename', '');
    }

    /**
     * @inheritDoc
     */
    public function getNickname(): string
    {
        return $this->getWpUser()->nickname;
    }

    /**
     * @inheritDoc
     */
    public function getPass(): string
    {
        return $this->get('user_pass', '');
    }

    /**
     * @inheritDoc
     */
    public function getRegistered(): string
    {
        return $this->get('user_registered', '');
    }

    /**
     * {@inheritDoc}
     *
     * @return RoleFactory[]|array
     */
    public function getRoles(): array
    {
        $roles = $this->getWpUser()->roles;

        $_roles = [];
        array_walk($roles, function ($role) use (&$_roles) {
            if ($_role = Role::get($role)) {
                $_roles[$role] = $_role;
            }
        });

        return $_roles;
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return $this->get('user_url', '');
    }

    /**
     * @inheritDoc
     */
    public function getWpUser(): WP_User
    {
        return $this->wp_user;
    }

    /**
     * @inheritDoc
     */
    public function hasRole(string $role): bool
    {
        return $this->roleIn([$role]);
    }

    /**
     * @inheritDoc
     */
    public function isLoggedIn(): bool
    {
        return wp_get_current_user()->exists();
    }

    /**
     * @inheritDoc
     */
    public function roleIn(array $roles): bool
    {
        return !!array_intersect(array_keys($this->getRoles()), $roles);
    }

    /**
     * @inheritDoc
     */
    public function save($userdata): void
    {
        $p = ParamsBag::createFromAttrs($userdata);
        $columns =  $this->db()->getConnection()->getSchemaBuilder()->getColumnListing($this->db()->getTable());

        $update = [];
        foreach ($columns as $col) {
            if ($p->has($col)) {
                $update[$col] = $p->get($col);
                if ($col === 'user_pass') {
                    $update[$col] = wp_hash_password($update[$col]);
                }
            }
        }

        $keys = [
            'first_name',
            'last_name',
            'nickname',
            'description',
            'rich_editing',
            'syntax_highlighting',
            'comment_shortcuts',
            'admin_color',
            'use_ssl',
            'show_admin_bar_front',
            'locale'
        ];
        foreach ($keys as $key) {
            if ($value = $p->pull($key)) {
                $p->set("meta.{$key}", $value);
            }
        }

        if ($update) {
            $this->db()->where(['ID' => $this->getId()])->update($update);
        }

        if ($p->has('meta')) {
            $this->saveMeta($p->get('meta'));
        }
    }

    /**
     * @inheritDoc
     */
    public function saveMeta($key, $value = null): void
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $k => $v) {
            $this->db()->saveMeta($k, $v);
        }
    }
}