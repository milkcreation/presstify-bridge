<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Model;

use Corcel\Model\Meta\PostMeta as CorcelPostmeta;
use Illuminate\Database\Eloquent\Builder;
use tiFy\Wordpress\Database\Concerns\MetaAwareTrait;

/**
 * @mixin Builder
 * @property $meta_value
 */
class Postmeta extends CorcelPostmeta
{
    use MetaAwareTrait;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
