<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Database;

use Corcel\Model\Builder\PostBuilder as CorcelPostBuilder;
use Corcel\Concerns\MetaFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @see https://github.com/corcel/corcel#posts
 *
 * @mixin Builder
 * @mixin Model
 * @mixin MetaFields
 * @mixin CorcelPostBuilder
 * @method static Postmeta createMeta($key, $value = null)
 * @method static mixed getMeta(string $meta_key)
 * @method static PostBuilder hasMeta(string|array $meta_key, mixed|null $value, string $operator = '=')
 * @method static PostBuilder hasMetaLike(string $key, string $value),
 * @method static boolean saveMeta($key, $value = null)
 */
interface PostBuilder
{
    /**
     * {@inheritDoc}
     *
     * @return PostBuilder
     */
    public static function query(): Builder;
}
