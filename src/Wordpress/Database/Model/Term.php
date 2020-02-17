<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Model;

use Corcel\Model\Term as CorcelTerm;
use tiFy\Database\Concerns\{ColumnsAwareTrait, ConnectionAwareTrait};
use tiFy\Wordpress\Contracts\Database\TaxonomyBuilder;
use tiFy\Wordpress\Database\Concerns\{BlogAwareTrait, MetaFieldsAwareTrait};

/**
 * @method static Termmeta createMeta($key, $value = null)
 * @method static mixed getMeta(string $meta_key)
 * @method static TaxonomyBuilder hasMeta(string|array $meta_key, mixed|null $value, string $operator = '=')
 * @method static TaxonomyBuilder hasMetaLike(string $key, string $value),
 * @method static boolean saveMeta($key, $value = null)
 */
class Term extends CorcelTerm implements TaxonomyBuilder
{
    use BlogAwareTrait, ConnectionAwareTrait, ColumnsAwareTrait, MetaFieldsAwareTrait;

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
}
