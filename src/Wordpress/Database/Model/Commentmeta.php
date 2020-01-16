<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Model;

use Corcel\Model\Meta\CommentMeta as CorcelCommentmeta;
use Illuminate\Database\Eloquent\Builder;
use tiFy\Wordpress\Database\Concerns\MetaAwareTrait;

/**
 * @mixin Builder
 */
class Commentmeta extends CorcelCommentmeta
{
    use MetaAwareTrait;
}
