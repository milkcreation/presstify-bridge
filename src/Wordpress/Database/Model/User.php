<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Model;

use Corcel\Model\User as CorcelUser;
use Illuminate\Support\{Carbon, Collection};
use tiFy\Database\{Concerns\ColumnsAwareTrait, Concerns\ConnectionAwareTrait};
use tiFy\Wordpress\Contracts\Database\UserBuilder;
use tiFy\Wordpress\Database\Concerns\{BlogAwareTrait, MetaFieldsAwareTrait};

/**
 * @method Usermeta createMeta($key, $value = null)
 * @method mixed getMeta(string $meta_key)
 * @method UserBuilder hasMeta(string|array $meta_key, mixed|null $value, string $operator = '=')
 * @method UserBuilder hasMetaLike(string $key, string $value),
 * @method boolean saveMeta($key, $value = null)
 *
 * @property-read int $ID
 * @property-read string $user_pass
 * @property-read string $user_login
 * @property-read string $user_nicename
 * @property-read string $user_url
 * @property-read string $user_email
 * @property-read string $display_name
 * @property-read string $nickname
 * @property-read string $first_name
 * @property-read string $last_name
 * @property-read string $description
 * @property-read string $rich_editing
 * @property-read string $syntax_highlighting
 * @property-read string $comment_shortcuts
 * @property-read string $admin_color
 * @property-read string $use_ssl
 * @property-read Carbon $user_registered
 * @property-read string $user_activation_key
 * @property-read string $spam
 * @property-read string $show_admin_bar_front
 * @property-read string $role
 * @property-read string $locale
 */
class User extends CorcelUser implements UserBuilder
{
    use BlogAwareTrait, ColumnsAwareTrait, ConnectionAwareTrait, MetaFieldsAwareTrait;

    /**
     * Cartographie des classes de gestion des métadonnées.
     * @var array
     */
    protected $builtInClasses = [
        Comment::class => CommentMeta::class,
        Post::class    => Postmeta::class,
        Term::class    => Termmeta::class,
        User::class    => Usermeta::class,
    ];

    /**
     * Nom de qualification de la connexion associé.
     * @var string
     */
    protected $connection = 'wp_user';

    /**
     * Récupération de l'activation des raccourcis de commentaires.
     *
     * @return bool
     */
    public function getCommentShortcutsAttribute(): bool
    {
        return filter_var($this->getMeta('comment_shortcuts'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Récupération de la locale associé.
     *
     * @return string
     */
    public function getLocaleAttribute(): string
    {
        return (string)$this->getMeta('locale');
    }

    /**
     * Récupération de l'activation de l'éditeur de contenu.
     *
     * @return bool
     */
    public function getRichEditingAttribute(): bool
    {
        return filter_var($this->getMeta('rich_editing'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Récupération de la liste des nom de qualification des roles d'affectation.
     *
     * @return array
     */
    public function getRolesAttribute(): array
    {
        $key = $this->getConnection()->getTablePrefix() . 'capabilities';

        if ($roles = $this->getMeta($key)) {
            return (new Collection($roles))->filter(function ($value) {
                return !empty($value);
            })->keys()->toArray();
        } else {
            return [];
        }
    }

    /**
     * Récupération du nom de qualification du rôle d'affectation initial.
     *
     * @return string
     */
    public function getRoleAttribute(): string
    {
        return ($roles = $this->getRolesAttribute()) ? $roles[0] : '';
    }

    /**
     * Récupération de l'activation de la barre d'administration sur le front.
     *
     * @return bool
     */
    public function getShowAdminBarFrontAttribute(): bool
    {
        return filter_var($this->getMeta('show_admin_bar_front'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Récupération de l'activation du spam (multisite uniquement).
     *
     * @return bool
     */
    public function getSpamAttribute(): bool
    {
        return filter_var($this->getMeta('spam'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Récupération de l'activation de la syntaxe.
     *
     * @return bool
     */
    public function getSyntaxHighlightingAttribute(): bool
    {
        return filter_var($this->getMeta('syntax_highlighting'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Récupération de l'activation du SSL.
     *
     * @return bool
     */
    public function getUseSslAttribute(): bool
    {
        return filter_var($this->getMeta('use_ssl'), FILTER_VALIDATE_BOOLEAN);
    }
}