<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Model;

use Corcel\Model\Taxonomy as CorcelTaxonomy;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Builder
 */
class Category extends CorcelTaxonomy
{
    /**
     * @var string
     */
    protected $taxonomy = 'category';
}
