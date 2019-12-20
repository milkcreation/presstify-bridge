<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Model;

use Corcel\Model\Taxonomy as CorcelTaxonomy;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Category
 * @package tiFy\Wordpress\Database\Model
 *
 * @mixin Builder
 */
class Category extends CorcelTaxonomy
{
    /**
     * @var string
     */
    protected $taxonomy = 'category';
}
