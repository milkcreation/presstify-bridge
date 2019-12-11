<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Model;

use Corcel\Model\Taxonomy as CorcelTaxonomy;
use Illuminate\Database\Eloquent\Builder;
use tiFy\Database\Concerns\{ColumnsAwareTrait, ConnectionAwareTrait};
use tiFy\Wordpress\Contracts\Database\TaxonomyBuilder;
use tiFy\Wordpress\Database\{Concerns\BlogAwareTrait, Concerns\MetaFieldsAwareTrait};

/**
 * @method static Termmeta createMeta($key, $value = null)
 * @method static mixed getMeta(string $meta_key)
 * @method static TaxonomyBuilder hasMeta(string|array $meta_key, mixed|null $value, string $operator = '=')
 * @method static TaxonomyBuilder hasMetaLike(string $key, string $value),
 * @method static boolean saveMeta($key, $value = null)
 *
 * @mixin Builder
 */
class TermTaxonomy extends CorcelTaxonomy
{
    use BlogAwareTrait, ConnectionAwareTrait, ColumnsAwareTrait, MetaFieldsAwareTrait;
}
