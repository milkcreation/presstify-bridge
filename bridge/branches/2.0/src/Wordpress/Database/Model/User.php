<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Model;

use Corcel\Model\User as CorcelUser;
use tiFy\Database\{Concerns\ColumnsAwareTrait, Concerns\ConnectionAwareTrait};
use tiFy\Wordpress\Contracts\Database\UserBuilder;
use tiFy\Wordpress\Database\Concerns\{BlogAwareTrait, MetaFieldsAwareTrait};

/**
 * @method Usermeta createMeta($key, $value = null)
 * @method mixed getMeta(string $meta_key)
 * @method UserBuilder hasMeta(string|array $meta_key, mixed|null $value, string $operator = '=')
 * @method UserBuilder hasMetaLike(string $key, string $value),
 * @method boolean saveMeta($key, $value = null)
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
}