<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts\Database;

use Corcel\{
    Concerns\MetaFields as CorcelMetaFields,
    Model\Builder\TaxonomyBuilder as CorcelTaxonomyBuilder,
    Model\Term as CorcelTerm};
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @see https://github.com/corcel/corcel#taxonomies
 *
 * @mixin CorcelMetaFields
 * @mixin CorcelTaxonomyBuilder
 * @mixin CorcelTerm
 * @mixin EloquentBuilder
 * @mixin EloquentModel
 *
 * @method static TaxonomyBuilder hasMeta(string|array $meta_key, mixed|null $value, string $operator= '=')
 * @method static TaxonomyBuilder hasMetaLike(string $key, string $value)
 */
interface TaxonomyBuilder
{

}
